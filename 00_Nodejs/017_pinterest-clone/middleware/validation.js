const { body, param, query, validationResult } = require('express-validator');
const User = require('../models/User');
const Board = require('../models/Board');

/**
 * Custom validation functions
 */
const customValidators = {
  // Check if username is available
  isUsernameAvailable: async (value) => {
    const user = await User.findOne({ username: value });
    if (user) {
      throw new Error('Username already exists');
    }
    return true;
  },

  // Check if email is available
  isEmailAvailable: async (value) => {
    const user = await User.findOne({ email: value });
    if (user) {
      throw new Error('Email already exists');
    }
    return true;
  },

  // Check if board exists and user has access
  hasBoardAccess: async (value, { req }) => {
    const board = await Board.findById(value);
    if (!board) {
      throw new Error('Board not found');
    }
    
    // Check if user owns the board or is collaborator
    if (board.owner.toString() !== req.user.id && 
        !board.collaborators.includes(req.user.id)) {
      throw new Error('Access denied to this board');
    }
    return true;
  },

  // Validate image file
  validateImageFile: (req, res, next) => {
    if (!req.file) {
      return next();
    }

    const allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!allowedMimeTypes.includes(req.file.mimetype)) {
      return res.status(400).json({
        error: 'Invalid file type. Only JPEG, PNG, and WebP images are allowed'
      });
    }

    if (req.file.size > maxSize) {
      return res.status(400).json({
        error: 'File too large. Maximum size is 5MB'
      });
    }

    next();
  },

  // Sanitize input
  sanitizeInput: (value) => {
    if (typeof value === 'string') {
      // Remove potentially dangerous characters
      return value.replace(/[<>]/g, '');
    }
    return value;
  }
};

/**
 * Validation rules for different endpoints
 */
const validationRules = {
  // Auth validations
  register: [
    body('username')
      .trim()
      .isLength({ min: 3, max: 30 })
      .withMessage('Username must be between 3 and 30 characters')
      .matches(/^[a-zA-Z0-9_]+$/)
      .withMessage('Username can only contain letters, numbers, and underscores')
      .custom(customValidators.isUsernameAvailable),

    body('email')
      .isEmail()
      .normalizeEmail()
      .withMessage('Please provide a valid email')
      .custom(customValidators.isEmailAvailable),

    body('password')
      .isLength({ min: 6 })
      .withMessage('Password must be at least 6 characters long')
      .matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/)
      .withMessage('Password must contain at least one lowercase letter, one uppercase letter, and one number'),

    body('confirmPassword')
      .custom((value, { req }) => {
        if (value !== req.body.password) {
          throw new Error('Passwords do not match');
        }
        return true;
      })
  ],

  login: [
    body('email')
      .isEmail()
      .normalizeEmail()
      .withMessage('Please provide a valid email'),

    body('password')
      .notEmpty()
      .withMessage('Password is required')
  ],

  // User validations
  updateProfile: [
    body('username')
      .optional()
      .trim()
      .isLength({ min: 3, max: 30 })
      .withMessage('Username must be between 3 and 30 characters')
      .matches(/^[a-zA-Z0-9_]+$/)
      .withMessage('Username can only contain letters, numbers, and underscores')
      .custom(async (value, { req }) => {
        if (value !== req.user.username) {
          const user = await User.findOne({ username: value });
          if (user) {
            throw new Error('Username already exists');
          }
        }
        return true;
      }),

    body('email')
      .optional()
      .isEmail()
      .normalizeEmail()
      .withMessage('Please provide a valid email')
      .custom(async (value, { req }) => {
        if (value !== req.user.email) {
          const user = await User.findOne({ email: value });
          if (user) {
            throw new Error('Email already exists');
          }
        }
        return true;
      }),

    body('bio')
      .optional()
      .trim()
      .isLength({ max: 500 })
      .withMessage('Bio must be less than 500 characters')
      .customSanitizer(customValidators.sanitizeInput)
  ],

  changePassword: [
    body('currentPassword')
      .notEmpty()
      .withMessage('Current password is required'),

    body('newPassword')
      .isLength({ min: 6 })
      .withMessage('New password must be at least 6 characters long')
      .matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/)
      .withMessage('Password must contain at least one lowercase letter, one uppercase letter, and one number'),

    body('confirmPassword')
      .custom((value, { req }) => {
        if (value !== req.body.newPassword) {
          throw new Error('Passwords do not match');
        }
        return true;
      })
  ],

  // Pin validations
  createPin: [
    body('title')
      .trim()
      .notEmpty()
      .withMessage('Title is required')
      .isLength({ max: 100 })
      .withMessage('Title must be less than 100 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('description')
      .optional()
      .trim()
      .isLength({ max: 500 })
      .withMessage('Description must be less than 500 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('link')
      .optional()
      .isURL()
      .withMessage('Please provide a valid URL'),

    body('boardId')
      .isMongoId()
      .withMessage('Valid board ID is required')
      .custom(customValidators.hasBoardAccess),

    body('tags')
      .optional()
      .custom((value) => {
        if (typeof value === 'string') {
          const tags = value.split(',').map(tag => tag.trim());
          if (tags.length > 10) {
            throw new Error('Maximum 10 tags allowed');
          }
          tags.forEach(tag => {
            if (tag.length > 20) {
              throw new Error('Each tag must be less than 20 characters');
            }
          });
        }
        return true;
      })
  ],

  updatePin: [
    body('title')
      .optional()
      .trim()
      .isLength({ max: 100 })
      .withMessage('Title must be less than 100 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('description')
      .optional()
      .trim()
      .isLength({ max: 500 })
      .withMessage('Description must be less than 500 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('link')
      .optional()
      .isURL()
      .withMessage('Please provide a valid URL'),

    body('boardId')
      .optional()
      .isMongoId()
      .withMessage('Valid board ID is required')
      .custom(customValidators.hasBoardAccess),

    body('tags')
      .optional()
      .custom((value) => {
        if (typeof value === 'string') {
          const tags = value.split(',').map(tag => tag.trim());
          if (tags.length > 10) {
            throw new Error('Maximum 10 tags allowed');
          }
          tags.forEach(tag => {
            if (tag.length > 20) {
              throw new Error('Each tag must be less than 20 characters');
            }
          });
        }
        return true;
      })
  ],

  // Board validations
  createBoard: [
    body('name')
      .trim()
      .notEmpty()
      .withMessage('Board name is required')
      .isLength({ max: 50 })
      .withMessage('Board name must be less than 50 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('description')
      .optional()
      .trim()
      .isLength({ max: 200 })
      .withMessage('Description must be less than 200 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('isPrivate')
      .optional()
      .isBoolean()
      .withMessage('isPrivate must be a boolean value')
  ],

  updateBoard: [
    body('name')
      .optional()
      .trim()
      .isLength({ max: 50 })
      .withMessage('Board name must be less than 50 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('description')
      .optional()
      .trim()
      .isLength({ max: 200 })
      .withMessage('Description must be less than 200 characters')
      .customSanitizer(customValidators.sanitizeInput),

    body('isPrivate')
      .optional()
      .isBoolean()
      .withMessage('isPrivate must be a boolean value')
  ],

  // Comment validations
  addComment: [
    body('text')
      .trim()
      .notEmpty()
      .withMessage('Comment text is required')
      .isLength({ max: 500 })
      .withMessage('Comment must be less than 500 characters')
      .customSanitizer(customValidators.sanitizeInput)
  ],

  // Search validations
  search: [
    query('q')
      .optional()
      .trim()
      .isLength({ max: 100 })
      .withMessage('Search query must be less than 100 characters')
      .customSanitizer(customValidators.sanitizeInput),

    query('page')
      .optional()
      .isInt({ min: 1 })
      .withMessage('Page must be a positive integer'),

    query('limit')
      .optional()
      .isInt({ min: 1, max: 50 })
      .withMessage('Limit must be between 1 and 50'),

    query('sort')
      .optional()
      .isIn(['latest', 'popular', 'oldest'])
      .withMessage('Sort must be one of: latest, popular, oldest')
  ],

  // ID parameter validations
  mongoId: [
    param('id')
      .isMongoId()
      .withMessage('Invalid ID format')
  ],

  userId: [
    param('userId')
      .isMongoId()
      .withMessage('Invalid user ID format')
  ],

  // Follow validations
  followUser: [
    param('userId')
      .isMongoId()
      .withMessage('Invalid user ID format')
      .custom(async (value, { req }) => {
        if (value === req.user.id) {
          throw new Error('You cannot follow yourself');
        }
        
        const user = await User.findById(value);
        if (!user) {
          throw new Error('User not found');
        }
        return true;
      })
  ]
};

/**
 * Middleware to handle validation results
 */
const handleValidationErrors = (req, res, next) => {
  const errors = validationResult(req);
  
  if (!errors.isEmpty()) {
    const errorMessages = errors.array().map(error => ({
      field: error.path,
      message: error.msg,
      value: error.value
    }));

    return res.status(400).json({
      error: 'Validation failed',
      details: errorMessages
    });
  }

  next();
};

/**
 * Conditional validation for file uploads
 */
const validateFileUpload = (req, res, next) => {
  if (req.file) {
    customValidators.validateImageFile(req, res, next);
  } else {
    next();
  }
};

/**
 * Sanitize query parameters
 */
const sanitizeQuery = [
  query('page').toInt(10),
  query('limit').toInt(10),
  query('q').customSanitizer(customValidators.sanitizeInput),
  query('sort').customSanitizer(customValidators.sanitizeInput),
  query('user').customSanitizer(customValidators.sanitizeInput)
];

/**
 * Rate limiting simulation (you can integrate with express-rate-limit)
 */
const simulateRateLimit = (req, res, next) => {
  // This is a simple simulation - in production, use express-rate-limit
  const rateLimitKey = `rate_limit_${req.ip}_${req.path}`;
  const now = Date.now();
  const windowMs = 15 * 60 * 1000; // 15 minutes
  const maxRequests = 100; // 100 requests per window
  
  // In a real implementation, you'd use Redis or memory store
  // This is just a placeholder to show the concept
  console.log(`Rate limit check for IP: ${req.ip}, Path: ${req.path}`);
  
  next();
};

/**
 * XSS protection middleware
 */
const xssProtection = (req, res, next) => {
  // Sanitize request body
  if (req.body) {
    Object.keys(req.body).forEach(key => {
      if (typeof req.body[key] === 'string') {
        req.body[key] = customValidators.sanitizeInput(req.body[key]);
      }
    });
  }

  // Sanitize query parameters
  if (req.query) {
    Object.keys(req.query).forEach(key => {
      if (typeof req.query[key] === 'string') {
        req.query[key] = customValidators.sanitizeInput(req.query[key]);
      }
    });
  }

  next();
};

module.exports = {
  validationRules,
  handleValidationErrors,
  validateFileUpload,
  sanitizeQuery,
  simulateRateLimit,
  xssProtection,
  customValidators
};