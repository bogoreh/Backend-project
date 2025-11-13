const express = require('express');
const router = express.Router();
const blogController = require('../controllers/blogController');

// Get all posts
router.get('/posts', blogController.getAllPosts);

// Show create form
router.get('/posts/new', blogController.showCreateForm);

// Create new post
router.post('/posts', blogController.createPost);

// Get single post
router.get('/posts/:id', blogController.getPost);

// Show edit form
router.get('/posts/:id/edit', blogController.showEditForm);

// Update post
router.put('/posts/:id', blogController.updatePost);

// Delete post
router.delete('/posts/:id', blogController.deletePost);

module.exports = router;