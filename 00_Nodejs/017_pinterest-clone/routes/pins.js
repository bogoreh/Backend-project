const express = require('express');
const router = express.Router();
const pinController = require('../controllers/pinController');
const { authenticate, optionalAuth } = require('../middleware/auth');
const upload = require('../middleware/upload');
const { body } = require('express-validator');

// Validation rules
const createPinValidation = [
  body('title').notEmpty().withMessage('Title is required'),
  body('boardId').isMongoId().withMessage('Valid board ID is required')
];

// Routes
router.post('/', authenticate, upload.single('image'), createPinValidation, pinController.createPin);
router.get('/', optionalAuth, pinController.getPins);
router.get('/:id', optionalAuth, pinController.getPin);
router.post('/:id/like', authenticate, pinController.likePin);
router.post('/:id/save', authenticate, pinController.savePin);
router.post('/:id/comment', authenticate, 
  body('text').notEmpty().withMessage('Comment text is required'),
  pinController.addComment
);

module.exports = router;