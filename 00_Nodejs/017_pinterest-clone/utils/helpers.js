const crypto = require('crypto');
const path = require('path');
const sharp = require('sharp');
const { v4: uuidv4 } = require('uuid');

/**
 * String utilities
 */
const helpers = {
  /**
   * Generate random string
   */
  generateRandomString: (length = 10) => {
    return crypto.randomBytes(length).toString('hex');
  },

  /**
   * Generate unique filename
   */
  generateUniqueFilename: (originalname) => {
    const ext = path.extname(originalname);
    const name = path.basename(originalname, ext);
    const timestamp = Date.now();
    const random = Math.random().toString(36).substring(2, 8);
    
    return `${name}-${timestamp}-${random}${ext}`.toLowerCase();
  },

  /**
   * Generate UUID
   */
  generateUUID: () => {
    return uuidv4();
  },

  /**
   * Slugify string
   */
  slugify: (text) => {
    return text
      .toString()
      .toLowerCase()
      .replace(/\s+/g, '-')
      .replace(/[^\w\-]+/g, '')
      .replace(/\-\-+/g, '-')
      .replace(/^-+/, '')
      .replace(/-+$/, '');
  },

  /**
   * Truncate text with ellipsis
   */
  truncate: (text, length = 100) => {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
  },

  /**
   * Extract hashtags from text
   */
  extractHashtags: (text) => {
    const hashtagRegex = /#(\w+)/g;
    const matches = text.match(hashtagRegex);
    return matches ? matches.map(tag => tag.substring(1).toLowerCase()) : [];
  },

  /**
   * Extract mentions from text
   */
  extractMentions: (text) => {
    const mentionRegex = /@(\w+)/g;
    const matches = text.match(mentionRegex);
    return matches ? matches.map(mention => mention.substring(1)) : [];
  },

  /**
   * Sanitize HTML (basic)
   */
  sanitizeHTML: (text) => {
    return text
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#x27;')
      .replace(/\//g, '&#x2F;');
  },

  /**
   * Format file size
   */
  formatFileSize: (bytes) => {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  },

  /**
   * Format date relative to now
   */
  formatRelativeTime: (date) => {
    const now = new Date();
    const diffInSeconds = Math.floor((now - new Date(date)) / 1000);
    
    if (diffInSeconds < 60) return 'just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)} days ago`;
    
    return new Date(date).toLocaleDateString();
  },

  /**
   * Format number with commas
   */
  formatNumber: (number) => {
    return new Intl.NumberFormat().format(number);
  },

  /**
   * Validate email format
   */
  isValidEmail: (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  },

  /**
   * Validate URL format
   */
  isValidURL: (url) => {
    try {
      new URL(url);
      return true;
    } catch {
      return false;
    }
  },

  /**
   * Generate pagination metadata
   */
  generatePagination: (page, limit, total, baseUrl, additionalParams = {}) => {
    const totalPages = Math.ceil(total / limit);
    const hasNext = page < totalPages;
    const hasPrev = page > 1;
    
    const pagination = {
      currentPage: page,
      totalPages,
      totalItems: total,
      itemsPerPage: limit,
      hasNext,
      hasPrev
    };

    // Generate URLs
    if (hasNext) {
      pagination.nextPage = `${baseUrl}?${new URLSearchParams({
        page: page + 1,
        limit,
        ...additionalParams
      })}`;
    }

    if (hasPrev) {
      pagination.prevPage = `${baseUrl}?${new URLSearchParams({
        page: page - 1,
        limit,
        ...additionalParams
      })}`;
    }

    return pagination;
  },

  /**
   * Deep clone object
   */
  deepClone: (obj) => {
    return JSON.parse(JSON.stringify(obj));
  },

  /**
   * Merge objects deeply
   */
  deepMerge: (target, source) => {
    const output = { ...target };
    
    if (helpers.isObject(target) && helpers.isObject(source)) {
      Object.keys(source).forEach(key => {
        if (helpers.isObject(source[key])) {
          if (!(key in target)) {
            output[key] = source[key];
          } else {
            output[key] = helpers.deepMerge(target[key], source[key]);
          }
        } else {
          output[key] = source[key];
        }
      });
    }
    
    return output;
  },

  /**
   * Check if value is object
   */
  isObject: (item) => {
    return item && typeof item === 'object' && !Array.isArray(item);
  },

  /**
   * Remove null/undefined properties from object
   */
  removeEmptyProperties: (obj) => {
    return Object.fromEntries(
      Object.entries(obj).filter(([_, v]) => v != null)
    );
  },

  /**
   * Generate password hash (using bcrypt in controller, this is for reference)
   */
  generatePasswordHash: async (password) => {
    // Note: In practice, use bcrypt in the controller
    // This is just a placeholder to show where it would be used
    return crypto.createHash('sha256').update(password).digest('hex');
  },

  /**
   * Create image thumbnail using Sharp
   */
  createThumbnail: async (buffer, width = 300, height = 300) => {
    return await sharp(buffer)
      .resize(width, height, {
        fit: 'cover',
        position: 'center'
      })
      .jpeg({ quality: 80 })
      .toBuffer();
  },

  /**
   * Optimize image
   */
  optimizeImage: async (buffer, quality = 80) => {
    return await sharp(buffer)
      .jpeg({ quality })
      .toBuffer();
  },

  /**
   * Get image dimensions
   */
  getImageDimensions: async (buffer) => {
    const metadata = await sharp(buffer).metadata();
    return {
      width: metadata.width,
      height: metadata.height,
      format: metadata.format
    };
  },

  /**
   * Generate color palette from image
   */
  generateColorPalette: async (buffer, colorCount = 5) => {
    // This is a simplified version - in practice, you might use more advanced libraries
    const stats = await sharp(buffer)
      .stats();
    
    const dominant = stats.dominant;
    return [dominant]; // Return array of dominant colors
  },

  /**
   * Delay execution
   */
  delay: (ms) => {
    return new Promise(resolve => setTimeout(resolve, ms));
  },

  /**
   * Retry function with exponential backoff
   */
  retryWithBackoff: async (fn, maxRetries = 3, baseDelay = 1000) => {
    let lastError;
    
    for (let attempt = 0; attempt < maxRetries; attempt++) {
      try {
        return await fn();
      } catch (error) {
        lastError = error;
        
        if (attempt < maxRetries - 1) {
          const delay = baseDelay * Math.pow(2, attempt);
          await helpers.delay(delay);
        }
      }
    }
    
    throw lastError;
  },

  /**
   * Generate cache key from request
   */
  generateCacheKey: (req) => {
    const keyParts = [
      req.method,
      req.originalUrl,
      JSON.stringify(req.query),
      JSON.stringify(req.body),
      req.user?.id || 'anonymous'
    ];
    
    return crypto.createHash('md5').update(keyParts.join('|')).digest('hex');
  },

  /**
   * Calculate reading time
   */
  calculateReadingTime: (text, wordsPerMinute = 200) => {
    const wordCount = text.split(/\s+/).length;
    return Math.ceil(wordCount / wordsPerMinute);
  },

  /**
   * Generate SEO-friendly description from text
   */
  generateSEODescription: (text, maxLength = 160) => {
    const cleanText = text.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim();
    return helpers.truncate(cleanText, maxLength);
  }
};

module.exports = helpers;