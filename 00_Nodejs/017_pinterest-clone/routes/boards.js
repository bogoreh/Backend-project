const express = require('express');
const router = express.Router();
const boardController = require('../controllers/boardController');
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
 * @route   POST /boards
 * @desc    Create a new board
 * @access  Private
 */
router.post(
  '/',
  authenticate,
  xssProtection,
  validationRules.createBoard,
  handleValidationErrors,
  boardController.createBoard
);

/**
 * @route   GET /boards/user/:userId?
 * @desc    Get all boards for a user (current user if no ID provided)
 * @access  Private
 */
router.get(
  '/user/:userId?',
  authenticate,
  sanitizeQuery,
  boardController.getUserBoards
);

/**
 * @route   GET /boards/saved
 * @desc    Get user's saved boards
 * @access  Private
 */
router.get(
  '/saved',
  authenticate,
  sanitizeQuery,
  boardController.getSavedBoards
);

/**
 * @route   GET /boards/search
 * @desc    Search boards by name, description, or user
 * @access  Public (with optional auth)
 */
router.get(
  '/search',
  optionalAuth,
  sanitizeQuery,
  validationRules.search,
  handleValidationErrors,
  boardController.searchBoards
);

/**
 * @route   GET /boards/:id
 * @desc    Get single board with pins
 * @access  Private
 */
router.get(
  '/:id',
  authenticate,
  validationRules.mongoId,
  handleValidationErrors,
  boardController.getBoard
);

/**
 * @route   PUT /boards/:id
 * @desc    Update board
 * @access  Private
 */
router.put(
  '/:id',
  authenticate,
  xssProtection,
  validationRules.mongoId,
  validationRules.updateBoard,
  handleValidationErrors,
  boardController.updateBoard
);

/**
 * @route   DELETE /boards/:id
 * @desc    Delete board and all its pins
 * @access  Private
 */
router.delete(
  '/:id',
  authenticate,
  validationRules.mongoId,
  handleValidationErrors,
  boardController.deleteBoard
);

/**
 * @route   POST /boards/:id/collaborators
 * @desc    Add collaborator to board
 * @access  Private
 */
router.post(
  '/:id/collaborators',
  authenticate,
  validationRules.mongoId,
  xssProtection,
  [
    body('username')
      .trim()
      .notEmpty()
      .withMessage('Username is required')
      .isLength({ min: 3, max: 30 })
      .withMessage('Username must be between 3 and 30 characters')
  ],
  handleValidationErrors,
  boardController.addCollaborator
);

/**
 * @route   DELETE /boards/:id/collaborators
 * @desc    Remove collaborator from board
 * @access  Private
 */
router.delete(
  '/:id/collaborators',
  authenticate,
  validationRules.mongoId,
  xssProtection,
  [
    body('userId')
      .isMongoId()
      .withMessage('Valid user ID is required')
  ],
  handleValidationErrors,
  boardController.removeCollaborator
);

/**
 * @route   POST /boards/:id/save
 * @desc    Save/unsave board
 * @access  Private
 */
router.post(
  '/:id/save',
  authenticate,
  validationRules.mongoId,
  handleValidationErrors,
  boardController.saveBoard
);

/**
 * @route   PUT /boards/:id/cover
 * @desc    Update board cover image
 * @access  Private
 */
router.put(
  '/:id/cover',
  authenticate,
  validationRules.mongoId,
  upload.single('coverImage'),
  validateFileUpload,
  boardController.updateCoverImage
);

/**
 * @route   GET /boards/:id/collaborators
 * @desc    Get board collaborators
 * @access  Private
 */
router.get(
  '/:id/collaborators',
  authenticate,
  validationRules.mongoId,
  handleValidationErrors,
  boardController.getBoardCollaborators
);

/**
 * @route   POST /boards/:id/follow
 * @desc    Follow/unfollow board
 * @access  Private
 */
router.post(
  '/:id/follow',
  authenticate,
  validationRules.mongoId,
  handleValidationErrors,
  boardController.followBoard
);

/**
 * @route   GET /boards/:id/activity
 * @desc    Get board activity (recent pins, collaborators)
 * @access  Private
 */
router.get(
  '/:id/activity',
  authenticate,
  validationRules.mongoId,
  sanitizeQuery,
  handleValidationErrors,
  boardController.getBoardActivity
);

const { body } = require('express-validator');

module.exports = router;