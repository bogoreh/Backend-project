const express = require('express');
const router = express.Router();

// Simple auth routes for now
router.get('/', (req, res) => {
  res.render('index', { 
    title: 'E-Voting System',
    message: 'Welcome to Secure Online Voting'
  });
});

router.get('/register', (req, res) => {
  res.render('register', { errors: null, formData: null });
});

router.post('/register', (req, res) => {
  // Simple registration logic for now
  req.session.userId = 'temp-user-id';
  res.redirect('/elections');
});

router.get('/login', (req, res) => {
  res.render('login', { errors: null, formData: null });
});

router.post('/login', (req, res) => {
  // Simple login logic for now
  req.session.userId = 'temp-user-id';
  res.redirect('/elections');
});

router.post('/logout', (req, res) => {
  req.session.destroy();
  res.redirect('/');
});

module.exports = router;