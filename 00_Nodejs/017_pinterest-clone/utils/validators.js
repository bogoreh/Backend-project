const mongoose = require('mongoose');
const User = require('../models/User');
const Pin = require('../models/Pin');
const Board = require('../models/Board');

/**
 * Custom validation functions for express-validator
 */
const customValidators = {
  /**
   * Check if MongoDB ObjectId is valid
   */
  isMongoId: (value) => {
    if (!value) return false;
    return mongoose.Types.ObjectId.isValid(value);
  },

  /**
   * Check if username is available
   */
  isUsernameAvailable: async (value) => {
    const user = await User.findOne({ username: value });
    return !user;
  },

  /**
   * Check if email is available
   */
  isEmailAvailable: async (value) => {
    const user = await User.findOne({ email: value });
    return !user;
  },

  /**
   * Check if user exists
   */
  userExists: async (value) => {
    if (!mongoose.Types.ObjectId.isValid(value)) return false;
    const user = await User.findById(value);
    return !!user;
  },

  /**
   * Check if pin exists
   */
  pinExists: async (value) => {
    if (!mongoose.Types.ObjectId.isValid(value)) return false;
    const pin = await Pin.findById(value);
    return !!pin;
  },

  /**
   * Check if board exists
   */
  boardExists: async (value) => {
    if (!mongoose.Types.ObjectId.isValid(value)) return false;
    const board = await Board.findById(value);
    return !!board;
  },

  /**
   * Check if user owns the board
   */
  isBoardOwner: async (value, { req }) => {
    if (!mongoose.Types.ObjectId.isValid(value)) return false;
    
    const board = await Board.findById(value);
    if (!board) return false;
    
    return board.owner.toString() === req.user.id;
  },

  /**
   * Check if user has access to board (owner or collaborator)
   */
  hasBoardAccess: async (value, { req }) => {
    if (!mongoose.Types.ObjectId.isValid(value)) return false;
    
    const board = await Board.findById(value);
    if (!board) return false;
    
    return board.owner.toString() === req.user.id || 
           board.collaborators.includes(req.user.id);
  },

  /**
   * Check if user owns the pin
   */
  isPinOwner: async (value, { req }) => {
    if (!mongoose.Types.ObjectId.isValid(value)) return false;
    
    const pin = await Pin.findById(value);
    if (!pin) return false;
    
    return pin.owner.toString() === req.user.id;
  },

  /**
   * Validate password strength
   */
  isStrongPassword: (value) => {
    const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;
    return strongPasswordRegex.test(value);
  },

  /**
   * Validate URL format and accessibility
   */
  isValidImageUrl: async (value) => {
    try {
      const url = new URL(value);
      
      // Check if it's a common image format
      const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.bmp', '.svg'];
      const isImage = imageExtensions.some(ext => 
        url.pathname.toLowerCase().endsWith(ext)
      );
      
      if (!isImage) return false;
      
      // Optional: Check if URL is accessible and returns image content-type
      // This can be heavy, so use cautiously
      return true;
      
    } catch {
      return false;
    }
  },

  /**
   * Validate file type by magic numbers
   */
  isImageFile: (buffer) => {
    const magicNumbers = {
      jpeg: [0xFF, 0xD8, 0xFF],
      png: [0x89, 0x50, 0x4E, 0x47],
      gif: [0x47, 0x49, 0x46],
      webp: [0x52, 0x49, 0x46, 0x46],
      bmp: [0x42, 0x4D]
    };

    for (const [format, signature] of Object.entries(magicNumbers)) {
      const matches = signature.every((byte, index) => buffer[index] === byte);
      if (matches) return true;
    }

    return false;
  },

  /**
   * Validate file size
   */
  isWithinFileSize: (buffer, maxSizeInMB = 5) => {
    const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
    return buffer.length <= maxSizeInBytes;
  },

  /**
   * Validate image dimensions
   */
  isWithinDimensions: async (buffer, maxWidth = 5000, maxHeight = 5000) => {
    const sharp = require('sharp');
    const metadata = await sharp(buffer).metadata();
    
    return metadata.width <= maxWidth && metadata.height <= maxHeight;
  },

  /**
   * Validate aspect ratio
   */
  hasValidAspectRatio: async (buffer, minRatio = 0.5, maxRatio = 2) => {
    const sharp = require('sharp');
    const metadata = await sharp(buffer).metadata();
    const aspectRatio = metadata.width / metadata.height;
    
    return aspectRatio >= minRatio && aspectRatio <= maxRatio;
  },

  /**
   * Check if tags array is within limits
   */
  isValidTagsArray: (value) => {
    if (!Array.isArray(value)) return false;
    if (value.length > 10) return false; // Max 10 tags
    
    return value.every(tag => 
      typeof tag === 'string' && 
      tag.length >= 1 && 
      tag.length <= 20 &&
      /^[a-zA-Z0-9_\-]+$/.test(tag)
    );
  },

  /**
   * Validate hex color code
   */
  isValidHexColor: (value) => {
    return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(value);
  },

  /**
   * Validate date is in the past
   */
  isPastDate: (value) => {
    return new Date(value) < new Date();
  },

  /**
   * Validate date is in the future
   */
  isFutureDate: (value) => {
    return new Date(value) > new Date();
  },

  /**
   * Validate array contains unique values
   */
  hasUniqueValues: (value) => {
    if (!Array.isArray(value)) return false;
    return new Set(value).size === value.length;
  },

  /**
   * Validate array length
   */
  hasArrayLength: (value, min, max) => {
    if (!Array.isArray(value)) return false;
    return value.length >= min && value.length <= max;
  },

  /**
   * Validate enum value
   */
  isEnumValue: (value, enumArray) => {
    return enumArray.includes(value);
  },

  /**
   * Validate search query length
   */
  isValidSearchQuery: (value) => {
    if (!value) return true; // Empty query is valid
    return value.length >= 1 && value.length <= 100;
  },

  /**
   * Sanitize and validate text input
   */
  sanitizeText: (value, maxLength = 1000) => {
    if (typeof value !== 'string') return value;
    
    // Remove potentially dangerous characters
    let sanitized = value
      .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
      .replace(/<[^>]*>/g, '')
      .trim();
    
    // Truncate if too long
    if (sanitized.length > maxLength) {
      sanitized = sanitized.substring(0, maxLength);
    }
    
    return sanitized;
  },

  /**
   * Validate username format
   */
  isValidUsername: (value) => {
    const usernameRegex = /^[a-zA-Z0-9_]{3,30}$/;
    return usernameRegex.test(value);
  },

  /**
   * Validate display name format
   */
  isValidDisplayName: (value) => {
    return value.length >= 2 && value.length <= 50;
  },

  /**
   * Validate bio length
   */
  isValidBio: (value) => {
    if (!value) return true; // Bio is optional
    return value.length <= 500;
  },

  /**
   * Validate pin title
   */
  isValidPinTitle: (value) => {
    return value.length >= 1 && value.length <= 100;
  },

  /**
   * Validate pin description
   */
  isValidPinDescription: (value) => {
    if (!value) return true; // Description is optional
    return value.length <= 500;
  },

  /**
   * Validate board name
   */
  isValidBoardName: (value) => {
    return value.length >= 1 && value.length <= 50;
  },

  /**
   * Validate board description
   */
  isValidBoardDescription: (value) => {
    if (!value) return true; // Description is optional
    return value.length <= 200;
  },

  /**
   * Validate comment text
   */
  isValidComment: (value) => {
    return value.length >= 1 && value.length <= 500;
  },

  /**
   * Rate limiting helper (simplified)
   */
  isWithinRateLimit: async (key, maxRequests, windowMs) => {
    // In practice, use redis or memory store
    // This is a simplified version
    return true;
  }
};

/**
 * Sanitization functions
 */
const sanitizers = {
  /**
   * Trim and lowercase email
   */
  normalizeEmail: (value) => {
    if (typeof value !== 'string') return value;
    return value.trim().toLowerCase();
  },

  /**
   * Trim string
   */
  trim: (value) => {
    if (typeof value !== 'string') return value;
    return value.trim();
  },

  /**
   * Convert to lowercase
   */
  toLowerCase: (value) => {
    if (typeof value !== 'string') return value;
    return value.toLowerCase();
  },

  /**
   * Convert to uppercase
   */
  toUpperCase: (value) => {
    if (typeof value !== 'string') return value;
    return value.toUpperCase();
  },

  /**
   * Escape HTML
   */
  escapeHTML: (value) => {
    if (typeof value !== 'string') return value;
    
    const escapeMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#x27;',
      '/': '&#x2F;'
    };
    
    return value.replace(/[&<>"'/]/g, char => escapeMap[char]);
  },

  /**
   * Remove special characters
   */
  removeSpecialChars: (value) => {
    if (typeof value !== 'string') return value;
    return value.replace(/[^a-zA-Z0-9\s]/g, '');
  },

  /**
   * Convert string to number
   */
  toInt: (value) => {
    if (typeof value === 'number') return value;
    const parsed = parseInt(value, 10);
    return isNaN(parsed) ? value : parsed;
  },

  /**
   * Convert string to float
   */
  toFloat: (value) => {
    if (typeof value === 'number') return value;
    const parsed = parseFloat(value);
    return isNaN(parsed) ? value : parsed;
  },

  /**
   * Convert string to boolean
   */
  toBoolean: (value) => {
    if (typeof value === 'boolean') return value;
    if (typeof value === 'string') {
      return value.toLowerCase() === 'true' || value === '1';
    }
    return !!value;
  }
};

module.exports = {
  customValidators,
  sanitizers
};