const Board = require('../models/Board');
const Pin = require('../models/Pin');
const User = require('../models/User');
const { validationResult } = require('express-validator');
const mongoose = require('mongoose');

/**
 * Create a new board
 */
exports.createBoard = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { name, description, isPrivate } = req.body;

    // Check if board name already exists for this user
    const existingBoard = await Board.findOne({
      name,
      owner: req.user.id
    });

    if (existingBoard) {
      return res.status(400).json({
        error: 'You already have a board with this name'
      });
    }

    const board = new Board({
      name,
      description: description || '',
      owner: req.user.id,
      isPrivate: isPrivate || false
    });

    await board.save();

    // Add board to user's boards array
    await User.findByIdAndUpdate(
      req.user.id,
      { $push: { boards: board._id } }
    );

    await board.populate('owner', 'username profilePicture');

    res.status(201).json({
      message: 'Board created successfully',
      board
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get all boards for a user
 */
exports.getUserBoards = async (req, res, next) => {
  try {
    const userId = req.params.userId || req.user.id;
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 12;
    const skip = (page - 1) * limit;

    // Check if user exists
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Build query - only show private boards to owner
    let query = { owner: userId };
    if (userId !== req.user.id) {
      query.isPrivate = false;
    }

    const boards = await Board.find(query)
      .populate('owner', 'username profilePicture')
      .populate({
        path: 'pins',
        options: { limit: 4 }, // Show first 4 pins as preview
        populate: {
          path: 'owner',
          select: 'username profilePicture'
        }
      })
      .sort({ updatedAt: -1 })
      .skip(skip)
      .limit(limit);

    const total = await Board.countDocuments(query);

    res.json({
      boards,
      currentPage: page,
      totalPages: Math.ceil(total / limit),
      totalBoards: total
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get single board with pins
 */
exports.getBoard = async (req, res, next) => {
  try {
    const boardId = req.params.id;
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 20;
    const skip = (page - 1) * limit;

    const board = await Board.findById(boardId)
      .populate('owner', 'username profilePicture bio')
      .populate('collaborators', 'username profilePicture');

    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    // Check if board is private and user has access
    if (board.isPrivate && 
        board.owner._id.toString() !== req.user.id && 
        !board.collaborators.some(collab => collab._id.toString() === req.user.id)) {
      return res.status(403).json({ error: 'Access denied. This board is private.' });
    }

    // Get pins for this board with pagination
    const pins = await Pin.find({ board: boardId })
      .populate('owner', 'username profilePicture')
      .sort({ createdAt: -1 })
      .skip(skip)
      .limit(limit);

    const totalPins = await Pin.countDocuments({ board: boardId });

    // Check if current user follows the board owner
    const currentUser = await User.findById(req.user.id);
    const isFollowing = currentUser ? currentUser.following.includes(board.owner._id) : false;

    // Check if current user is collaborator
    const isCollaborator = board.collaborators.some(
      collab => collab._id.toString() === req.user.id
    );

    res.json({
      board,
      pins,
      isFollowing,
      isCollaborator,
      currentPage: page,
      totalPages: Math.ceil(totalPins / limit),
      totalPins
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Update board
 */
exports.updateBoard = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { name, description, isPrivate } = req.body;
    const boardId = req.params.id;

    const board = await Board.findById(boardId);

    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    // Check if user owns the board or is collaborator
    if (board.owner.toString() !== req.user.id && 
        !board.collaborators.includes(req.user.id)) {
      return res.status(403).json({ error: 'Access denied' });
    }

    // Only owner can change privacy and name
    if (board.owner.toString() === req.user.id) {
      if (name) board.name = name;
      if (typeof isPrivate !== 'undefined') board.isPrivate = isPrivate;
    }

    // Collaborators can only update description
    if (description) board.description = description;

    await board.save();
    await board.populate('owner', 'username profilePicture');
    await board.populate('collaborators', 'username profilePicture');

    res.json({
      message: 'Board updated successfully',
      board
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Delete board
 */
exports.deleteBoard = async (req, res, next) => {
  try {
    const boardId = req.params.id;

    const board = await Board.findById(boardId);

    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    // Check if user owns the board
    if (board.owner.toString() !== req.user.id) {
      return res.status(403).json({ error: 'Access denied' });
    }

    // Delete all pins in this board
    await Pin.deleteMany({ board: boardId });

    // Remove board from user's boards array
    await User.findByIdAndUpdate(
      req.user.id,
      { $pull: { boards: boardId } }
    );

    // Remove board from collaborators' saved boards if any
    await User.updateMany(
      { savedBoards: boardId },
      { $pull: { savedBoards: boardId } }
    );

    await Board.findByIdAndDelete(boardId);

    res.json({
      message: 'Board and all its pins deleted successfully'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Add collaborator to board
 */
exports.addCollaborator = async (req, res, next) => {
  try {
    const { username } = req.body;
    const boardId = req.params.id;

    const board = await Board.findById(boardId);
    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    // Check if user owns the board
    if (board.owner.toString() !== req.user.id) {
      return res.status(403).json({ error: 'Only board owner can add collaborators' });
    }

    const collaborator = await User.findOne({ username });
    if (!collaborator) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Check if user is trying to add themselves
    if (collaborator._id.toString() === req.user.id) {
      return res.status(400).json({ error: 'You cannot add yourself as a collaborator' });
    }

    // Check if user is already a collaborator
    if (board.collaborators.includes(collaborator._id)) {
      return res.status(400).json({ error: 'User is already a collaborator' });
    }

    board.collaborators.push(collaborator._id);
    await board.save();

    await board.populate('collaborators', 'username profilePicture');

    res.json({
      message: 'Collaborator added successfully',
      board
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Remove collaborator from board
 */
exports.removeCollaborator = async (req, res, next) => {
  try {
    const { userId } = req.body;
    const boardId = req.params.id;

    const board = await Board.findById(boardId);
    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    // Check if user owns the board
    if (board.owner.toString() !== req.user.id) {
      return res.status(403).json({ error: 'Only board owner can remove collaborators' });
    }

    // Check if collaborator exists
    if (!board.collaborators.includes(userId)) {
      return res.status(400).json({ error: 'User is not a collaborator' });
    }

    board.collaborators.pull(userId);
    await board.save();

    await board.populate('collaborators', 'username profilePicture');

    res.json({
      message: 'Collaborator removed successfully',
      board
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Save board to user's profile
 */
exports.saveBoard = async (req, res, next) => {
  try {
    const boardId = req.params.id;
    const userId = req.user.id;

    const board = await Board.findById(boardId);
    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    // Check if board is private and user has access
    if (board.isPrivate && 
        board.owner.toString() !== userId && 
        !board.collaborators.includes(userId)) {
      return res.status(403).json({ error: 'Access denied. This board is private.' });
    }

    const user = await User.findById(userId);
    const hasSaved = user.savedBoards ? user.savedBoards.includes(boardId) : false;

    if (hasSaved) {
      user.savedBoards.pull(boardId);
    } else {
      if (!user.savedBoards) user.savedBoards = [];
      user.savedBoards.push(boardId);
    }

    await user.save();

    res.json({
      saved: !hasSaved,
      message: hasSaved ? 'Board removed from saved' : 'Board saved successfully'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get user's saved boards
 */
exports.getSavedBoards = async (req, res, next) => {
  try {
    const userId = req.user.id;
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 12;
    const skip = (page - 1) * limit;

    const user = await User.findById(userId).populate({
      path: 'savedBoards',
      populate: [
        {
          path: 'owner',
          select: 'username profilePicture'
        },
        {
          path: 'pins',
          options: { limit: 4 },
          populate: {
            path: 'owner',
            select: 'username profilePicture'
          }
        }
      ]
    });

    if (!user.savedBoards) {
      return res.json({
        boards: [],
        currentPage: 1,
        totalPages: 0,
        totalBoards: 0
      });
    }

    // Manual pagination since we already populated
    const savedBoards = user.savedBoards || [];
    const paginatedBoards = savedBoards.slice(skip, skip + limit);

    res.json({
      boards: paginatedBoards,
      currentPage: page,
      totalPages: Math.ceil(savedBoards.length / limit),
      totalBoards: savedBoards.length
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Search boards
 */
exports.searchBoards = async (req, res, next) => {
  try {
    const { q: searchQuery, user: username } = req.query;
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 12;
    const skip = (page - 1) * limit;

    let query = { isPrivate: false }; // Only show public boards in search

    // Search by board name or description
    if (searchQuery) {
      query.$or = [
        { name: { $regex: searchQuery, $options: 'i' } },
        { description: { $regex: searchQuery, $options: 'i' } }
      ];
    }

    // Filter by user
    if (username) {
      const user = await User.findOne({ username });
      if (user) {
        query.owner = user._id;
      } else {
        return res.json({
          boards: [],
          currentPage: 1,
          totalPages: 0,
          totalBoards: 0
        });
      }
    }

    const boards = await Board.find(query)
      .populate('owner', 'username profilePicture')
      .populate({
        path: 'pins',
        options: { limit: 4 },
        populate: {
          path: 'owner',
          select: 'username profilePicture'
        }
      })
      .sort({ updatedAt: -1 })
      .skip(skip)
      .limit(limit);

    const total = await Board.countDocuments(query);

    res.json({
      boards,
      currentPage: page,
      totalPages: Math.ceil(total / limit),
      totalBoards: total
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Update board cover image
 */
exports.updateCoverImage = async (req, res, next) => {
  try {
    const boardId = req.params.id;

    const board = await Board.findById(boardId);
    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    // Check if user owns the board or is collaborator
    if (board.owner.toString() !== req.user.id && 
        !board.collaborators.includes(req.user.id)) {
      return res.status(403).json({ error: 'Access denied' });
    }

    if (!req.file) {
      return res.status(400).json({ error: 'Cover image is required' });
    }

    // Delete old cover image if exists
    if (board.coverImage && board.coverImage.publicId) {
      const cloudinary = require('../config/cloudinary');
      await cloudinary.uploader.destroy(board.coverImage.publicId);
    }

    board.coverImage = {
      url: req.file.path,
      publicId: req.file.filename
    };

    await board.save();

    res.json({
      message: 'Cover image updated successfully',
      board
    });
  } catch (error) {
    next(error);
  }
};