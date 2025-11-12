const mongoose = require('mongoose');

const commentSchema = new mongoose.Schema({
  // Content of the comment
  text: {
    type: String,
    required: [true, 'Comment text is required'],
    trim: true,
    maxlength: [500, 'Comment cannot exceed 500 characters'],
    minlength: [1, 'Comment cannot be empty']
  },

  // User who created the comment
  author: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: [true, 'Comment author is required']
  },

  // Pin that the comment belongs to
  pin: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Pin',
    required: [true, 'Comment must belong to a pin'],
    index: true
  },

  // Parent comment for nested replies (null for top-level comments)
  parentComment: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Comment',
    default: null,
    index: true
  },

  // Array of replies to this comment
  replies: [{
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Comment'
  }],

  // Likes on the comment
  likes: [{
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    index: true
  }],

  // Number of likes (denormalized for performance)
  likesCount: {
    type: Number,
    default: 0,
    min: 0
  },

  // Number of replies (denormalized for performance)
  repliesCount: {
    type: Number,
    default: 0,
    min: 0
  },

  // Flag for inappropriate content
  isFlagged: {
    type: Boolean,
    default: false
  },

  // Reason for flagging
  flaggedReason: {
    type: String,
    enum: ['spam', 'harassment', 'hate_speech', 'inappropriate', 'other'],
    default: null
  },

  // User who flagged the comment
  flaggedBy: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User'
  },

  // Soft delete flag
  isDeleted: {
    type: Boolean,
    default: false
  },

  // Deletion reason (if deleted by admin/moderator)
  deletionReason: {
    type: String,
    enum: ['user_deleted', 'admin_removed', 'violated_terms', null],
    default: null
  },

  // Edited flag and history
  isEdited: {
    type: Boolean,
    default: false
  },

  // Edit history (stores previous versions)
  editHistory: [{
    text: String,
    editedAt: {
      type: Date,
      default: Date.now
    },
    reason: {
      type: String,
      maxlength: 200
    }
  }],

  // Last edited timestamp
  lastEditedAt: {
    type: Date,
    default: null
  },

  // Mentioned users in the comment
  mentions: [{
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    index: true
  }],

  // Hashtags extracted from comment text
  hashtags: [{
    type: String,
    trim: true,
    lowercase: true
  }],

  // Comment visibility settings
  visibility: {
    type: String,
    enum: ['public', 'followers_only', 'private'],
    default: 'public'
  },

  // Pinned comment (by pin owner)
  isPinned: {
    type: Boolean,
    default: false
  },

  // Pinned by (pin owner or moderator)
  pinnedBy: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User'
  }
}, {
  timestamps: true,
  toJSON: { virtuals: true },
  toObject: { virtuals: true }
});

/**
 * Virtual for comment URL
 */
commentSchema.virtual('url').get(function() {
  return `/pins/${this.pin}/comments/${this._id}`;
});

/**
 * Virtual for checking if comment has replies
 */
commentSchema.virtual('hasReplies').get(function() {
  return this.repliesCount > 0;
});

/**
 * Virtual for checking if comment is a reply
 */
commentSchema.virtual('isReply').get(function() {
  return this.parentComment !== null;
});

/**
 * Indexes for better query performance
 */
commentSchema.index({ pin: 1, createdAt: -1 }); // For getting comments by pin
commentSchema.index({ author: 1, createdAt: -1 }); // For getting user's comments
commentSchema.index({ parentComment: 1, createdAt: 1 }); // For getting replies
commentSchema.index({ likesCount: -1 }); // For popular comments
commentSchema.index({ isPinned: -1, createdAt: -1 }); // For pinned comments
commentSchema.index({ isFlagged: 1 }); // For moderation
commentSchema.index({ 'hashtags': 1 }); // For hashtag search
commentSchema.index({ 'mentions': 1 }); // For mention notifications

/**
 * Pre-save middleware to update denormalized counts and extract metadata
 */
commentSchema.pre('save', function(next) {
  // Update likes count
  if (this.isModified('likes')) {
    this.likesCount = this.likes.length;
  }

  // Update replies count
  if (this.isModified('replies')) {
    this.repliesCount = this.replies.length;
  }

  // Extract hashtags from comment text
  if (this.isModified('text')) {
    this.extractHashtags();
    this.extractMentions();
  }

  // Handle edit history
  if (this.isModified('text') && !this.isNew) {
    this.isEdited = true;
    this.lastEditedAt = new Date();
    
    // Add to edit history (keep last 5 edits)
    if (this.editHistory.length >= 5) {
      this.editHistory.shift();
    }
  }

  next();
});

/**
 * Post-save middleware to update parent comment's replies count
 */
commentSchema.post('save', async function() {
  if (this.parentComment && this.isNew) {
    await this.constructor.findByIdAndUpdate(
      this.parentComment,
      { 
        $inc: { repliesCount: 1 },
        $push: { replies: this._id }
      }
    );
  }
});

/**
 * Post-remove middleware to clean up relationships
 */
commentSchema.post('remove', async function() {
  try {
    // Remove this comment from parent's replies array
    if (this.parentComment) {
      await this.constructor.findByIdAndUpdate(
        this.parentComment,
        { 
          $inc: { repliesCount: -1 },
          $pull: { replies: this._id }
        }
      );
    }

    // Remove all replies to this comment
    if (this.replies.length > 0) {
      await this.constructor.deleteMany({ _id: { $in: this.replies } });
    }

    // Remove this comment from pin's comments array
    const Pin = mongoose.model('Pin');
    await Pin.findByIdAndUpdate(
      this.pin,
      { $pull: { comments: this._id } }
    );
  } catch (error) {
    console.error('Error cleaning up comment relationships:', error);
  }
});

/**
 * Instance methods
 */
commentSchema.methods = {
  /**
   * Extract hashtags from comment text
   */
  extractHashtags: function() {
    const hashtagRegex = /#(\w+)/g;
    const matches = this.text.match(hashtagRegex);
    this.hashtags = matches ? matches.map(tag => tag.substring(1).toLowerCase()) : [];
  },

  /**
   * Extract user mentions from comment text
   */
  extractMentions: function() {
    const mentionRegex = /@(\w+)/g;
    const matches = this.text.match(mentionRegex);
    const mentionedUsernames = matches ? matches.map(mention => mention.substring(1)) : [];
    
    // We'll populate the actual user IDs in the controller
    this.mentions = []; // Reset, will be populated later
  },

  /**
   * Check if user has liked this comment
   */
  isLikedBy: function(userId) {
    return this.likes.includes(userId);
  },

  /**
   * Toggle like for a user
   */
  toggleLike: async function(userId) {
    const hasLiked = this.isLikedBy(userId);
    
    if (hasLiked) {
      this.likes.pull(userId);
    } else {
      this.likes.push(userId);
    }
    
    this.likesCount = this.likes.length;
    await this.save();
    return !hasLiked;
  },

  /**
   * Soft delete comment
   */
  softDelete: function(reason = 'user_deleted') {
    this.isDeleted = true;
    this.deletionReason = reason;
    this.text = '[This comment has been deleted]';
    this.isEdited = false;
    this.editHistory = [];
    this.mentions = [];
    this.hashtags = [];
  },

  /**
   * Add edit to history
   */
  addEditHistory: function(reason = '') {
    this.editHistory.push({
      text: this.text,
      editedAt: new Date(),
      reason: reason
    });
  },

  /**
   * Check if user can edit this comment
   */
  canEdit: function(userId) {
    return this.author.toString() === userId.toString() && !this.isDeleted;
  },

  /**
   * Check if user can delete this comment
   */
  canDelete: function(userId, isModerator = false) {
    return this.author.toString() === userId.toString() || isModerator;
  }
};

/**
 * Static methods
 */
commentSchema.statics = {
  /**
   * Get comments by pin with pagination and population
   */
  getByPin: function(pinId, options = {}) {
    const {
      page = 1,
      limit = 20,
      sortBy = 'createdAt',
      sortOrder = 'desc',
      includeReplies = false,
      user = null
    } = options;

    const skip = (page - 1) * limit;
    const sort = { [sortBy]: sortOrder === 'desc' ? -1 : 1 };

    // Base query - only non-deleted comments
    let query = { 
      pin: pinId, 
      isDeleted: false,
      parentComment: includeReplies ? { $exists: true } : null
    };

    // If not including replies, only get top-level comments
    if (!includeReplies) {
      query.parentComment = null;
    }

    return this.find(query)
      .populate('author', 'username profilePicture')
      .populate({
        path: 'replies',
        match: { isDeleted: false },
        options: { sort: { createdAt: 1 }, limit: 5 },
        populate: {
          path: 'author',
          select: 'username profilePicture'
        }
      })
      .sort(sort)
      .skip(skip)
      .limit(limit)
      .lean();
  },

  /**
   * Get comment with full thread
   */
  getCommentThread: function(commentId, depth = 2) {
    return this.findById(commentId)
      .populate('author', 'username profilePicture')
      .populate({
        path: 'replies',
        match: { isDeleted: false },
        populate: [
          {
            path: 'author',
            select: 'username profilePicture'
          },
          {
            path: 'replies',
            match: { isDeleted: false },
            options: { limit: 10 },
            populate: {
              path: 'author',
              select: 'username profilePicture'
            }
          }
        ]
      })
      .lean();
  },

  /**
   * Get user's comments with pagination
   */
  getByUser: function(userId, options = {}) {
    const {
      page = 1,
      limit = 20,
      includeDeleted = false
    } = options;

    const skip = (page - 1) * limit;

    const query = { author: userId };
    if (!includeDeleted) {
      query.isDeleted = false;
    }

    return this.find(query)
      .populate('pin', 'title image.url')
      .populate('author', 'username profilePicture')
      .sort({ createdAt: -1 })
      .skip(skip)
      .limit(limit)
      .lean();
  },

  /**
   * Search comments by text or hashtags
   */
  search: function(searchQuery, options = {}) {
    const {
      page = 1,
      limit = 20,
      pinId = null,
      userId = null
    } = options;

    const skip = (page - 1) * limit;

    let query = {
      isDeleted: false,
      $text: { $search: searchQuery }
    };

    if (pinId) query.pin = pinId;
    if (userId) query.author = userId;

    return this.find(query)
      .populate('author', 'username profilePicture')
      .populate('pin', 'title image.url')
      .sort({ score: { $meta: 'textScore' } })
      .skip(skip)
      .limit(limit)
      .lean();
  },

  /**
   * Get popular comments (most liked)
   */
  getPopular: function(pinId, limit = 10) {
    return this.find({
      pin: pinId,
      isDeleted: false,
      likesCount: { $gt: 0 }
    })
    .populate('author', 'username profilePicture')
    .sort({ likesCount: -1, createdAt: -1 })
    .limit(limit)
    .lean();
  },

  /**
   * Get flagged comments for moderation
   */
  getFlagged: function(options = {}) {
    const {
      page = 1,
      limit = 20,
      reason = null
    } = options;

    const skip = (page - 1) * limit;

    const query = { isFlagged: true };
    if (reason) query.flaggedReason = reason;

    return this.find(query)
      .populate('author', 'username profilePicture')
      .populate('flaggedBy', 'username')
      .populate('pin', 'title')
      .sort({ createdAt: -1 })
      .skip(skip)
      .limit(limit)
      .lean();
  },

  /**
   * Update mentions in batch
   */
  updateMentions: async function(commentId, mentionedUserIds) {
    return this.findByIdAndUpdate(
      commentId,
      { mentions: mentionedUserIds },
      { new: true }
    );
  }
};

/**
 * Text search index
 */
commentSchema.index({
  text: 'text',
  hashtags: 'text'
}, {
  weights: {
    text: 10,
    hashtags: 5
  },
  name: 'comment_text_search'
});

module.exports = mongoose.model('Comment', commentSchema);