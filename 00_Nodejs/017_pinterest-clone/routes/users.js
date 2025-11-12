const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');
const { authenticate, optionalAuth } = require('../middleware/auth');
const upload = require('../middleware/upload');
const { 
  validationRules, 
  handleValidationErrors,
  sanitizeQuery,
  xssProtection,
  validateFileUpload
} = require('../middleware/validation');

/**
 * @route   GET /users/profile
 * @desc    Get current user's full profile
 * @access  Private
 */
router.get(
  '/profile',
  authenticate,
  userController.getCurrentUserProfile
);

/**
 * @route   GET /users/:username
 * @desc    Get user public profile
 * @access  Public (with optional auth)
 */
router.get(
  '/:username',
  optionalAuth,
  userController.getUserByUsername
);

/**
 * @route   PUT /users/profile
 * @desc    Update user profile
 * @access  Private
 */
router.put(
  '/profile',
  authenticate,
  upload.single('profilePicture'),
  validateFileUpload,
  xssProtection,
  validationRules.updateProfile,
  handleValidationErrors,
  userController.updateProfile
);

/**
 * @route   PUT /users/password
 * @desc    Change user password
 * @access  Private
 */
router.put(
  '/password',
  authenticate,
  xssProtection,
  validationRules.changePassword,
  handleValidationErrors,
  userController.changePassword
);

/**
 * @route   POST /users/follow/:userId
 * @desc    Follow/unfollow a user
 * @access  Private
 */
router.post(
  '/follow/:userId',
  authenticate,
  validationRules.followUser,
  handleValidationErrors,
  userController.toggleFollow
);

/**
 * @route   GET /users/following
 * @desc    Get users that current user is following
 * @access  Private
 */
router.get(
  '/following',
  authenticate,
  sanitizeQuery,
  userController.getFollowing
);

/**
 * @route   GET /users/followers
 * @desc    Get users who follow current user
 * @access  Private
 */
router.get(
  '/followers',
  authenticate,
  sanitizeQuery,
  userController.getFollowers
);

/**
 * @route   GET /users/:username/following
 * @desc    Get users that a user is following
 * @access  Public
 */
router.get(
  '/:username/following',
  optionalAuth,
  sanitizeQuery,
  userController.getUserFollowing
);

/**
 * @route   GET /users/:username/followers
 * @desc    Get users who follow a user
 * @access  Public
 */
router.get(
  '/:username/followers',
  optionalAuth,
  sanitizeQuery,
  userController.getUserFollowers
);

/**
 * @route   GET /users/:username/pins
 * @desc    Get user's pins
 * @access  Public
 */
router.get(
  '/:username/pins',
  optionalAuth,
  sanitizeQuery,
  validationRules.search,
  handleValidationErrors,
  userController.getUserPins
);

/**
 * @route   GET /users/:username/boards
 * @desc    Get user's boards
 * @access  Public
 */
router.get(
  '/:username/boards',
  optionalAuth,
  sanitizeQuery,
  userController.getUserPublicBoards
);

/**
 * @route   GET /users/:username/liked-pins
 * @desc    Get user's liked pins
 * @access  Public
 */
router.get(
  '/:username/liked-pins',
  optionalAuth,
  sanitizeQuery,
  userController.getUserLikedPins
);

/**
 * @route   GET /users/search
 * @desc    Search users by username
 * @access  Public
 */
router.get(
  '/search',
  optionalAuth,
  sanitizeQuery,
  [
    validationRules.search[0], // Search query validation
    ...validationRules.search.slice(2) // Pagination validation
  ],
  handleValidationErrors,
  userController.searchUsers
);

/**
 * @route   GET /users/suggestions
 * @desc    Get user suggestions to follow
 * @access  Private
 */
router.get(
  '/suggestions',
  authenticate,
  sanitizeQuery,
  userController.getUserSuggestions
);

/**
 * @route   GET /users/activity
 * @desc    Get current user's activity (follows, likes, comments)
 * @access  Private
 */
router.get(
  '/activity',
  authenticate,
  sanitizeQuery,
  userController.getUserActivity
);

/**
 * @route   POST /users/deactivate
 * @desc    Deactivate user account
 * @access  Private
 */
router.post(
  '/deactivate',
  authenticate,
  userController.deactivateAccount
);

/**
 * @route   POST /users/reactivate
 * @desc    Reactivate user account
 * @access  Public
 */
router.post(
  '/reactivate',
  xssProtection,
  [
    body('email')
      .isEmail()
      .withMessage('Valid email is required'),
    body('password')
      .notEmpty()
      .withMessage('Password is required')
  ],
  handleValidationErrors,
  userController.reactivateAccount
);

/**
 * @route   DELETE /users/account
 * @desc    Permanently delete user account
 * @access  Private
 */
router.delete(
  '/account',
  authenticate,
  userController.deleteAccount
);

/**
 * @route   GET /users/notifications
 * @desc    Get user notifications
 * @access  Private
 */
router.get(
  '/notifications',
  authenticate,
  sanitizeQuery,
  userController.getNotifications
);

/**
 * @route   PUT /users/notifications/read
 * @desc    Mark notifications as read
 * @access  Private
 */
router.put(
  '/notifications/read',
  authenticate,
  userController.markNotificationsAsRead
);

/**
 * @route   PUT /users/notifications/settings
 * @desc    Update notification settings
 * @access  Private
 */
router.put(
  '/notifications/settings',
  authenticate,
  xssProtection,
  [
    body('emailNotifications')
      .optional()
      .isBoolean()
      .withMessage('Email notifications must be a boolean'),
    body('pushNotifications')
      .optional()
      .isBoolean()
      .withMessage('Push notifications must be a boolean'),
    body('newsletter')
      .optional()
      .isBoolean()
      .withMessage('Newsletter must be a boolean')
  ],
  handleValidationErrors,
  userController.updateNotificationSettings
);

const { body } = require('express-validator');

module.exports = router;