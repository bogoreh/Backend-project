const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const { authenticate } = require('../middleware/auth');
const { 
  validationRules, 
  handleValidationErrors,
  xssProtection 
} = require('../middleware/validation');

/**
 * @route   POST /auth/register
 * @desc    Register a new user
 * @access  Public
 */
router.post(
  '/register',
  xssProtection,
  validationRules.register,
  handleValidationErrors,
  authController.register
);

/**
 * @route   POST /auth/login
 * @desc    Login user
 * @access  Public
 */
router.post(
  '/login',
  xssProtection,
  validationRules.login,
  handleValidationErrors,
  authController.login
);

/**
 * @route   POST /auth/logout
 * @desc    Logout user
 * @access  Private
 */
router.post(
  '/logout',
  authenticate,
  authController.logout
);

/**
 * @route   GET /auth/me
 * @desc    Get current user profile
 * @access  Private
 */
router.get(
  '/me',
  authenticate,
  authController.getProfile
);

/**
 * @route   POST /auth/refresh-token
 * @desc    Refresh access token
 * @access  Private
 */
router.post(
  '/refresh-token',
  authenticate,
  authController.refreshToken
);

/**
 * @route   POST /auth/forgot-password
 * @desc    Request password reset
 * @access  Public
 */
router.post(
  '/forgot-password',
  xssProtection,
  [
    validationRules.login[0] // Email validation
  ],
  handleValidationErrors,
  authController.forgotPassword
);

/**
 * @route   POST /auth/reset-password/:token
 * @desc    Reset password with token
 * @access  Public
 */
router.post(
  '/reset-password/:token',
  xssProtection,
  validationRules.changePassword.slice(1), // Exclude currentPassword
  handleValidationErrors,
  authController.resetPassword
);

/**
 * @route   POST /auth/verify-email
 * @desc    Request email verification
 * @access  Private
 */
router.post(
  '/verify-email',
  authenticate,
  authController.requestEmailVerification
);

/**
 * @route   GET /auth/verify-email/:token
 * @desc    Verify email with token
 * @access  Public
 */
router.get(
  '/verify-email/:token',
  authController.verifyEmail
);

/**
 * @route   GET /auth/check-username/:username
 * @desc    Check if username is available
 * @access  Public
 */
router.get(
  '/check-username/:username',
  authController.checkUsernameAvailability
);

/**
 * @route   GET /auth/check-email/:email
 * @desc    Check if email is available
 * @access  Public
 */
router.get(
  '/check-email/:email',
  authController.checkEmailAvailability
);

module.exports = router;