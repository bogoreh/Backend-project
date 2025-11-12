const User = require('../models/User');
const { validationResult } = require('express-validator');

exports.register = async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).render('register', {
        errors: errors.array(),
        formData: req.body
      });
    }

    const { email, password, name, voterId } = req.body;

    // Check if user already exists
    const existingUser = await User.findOne({ 
      $or: [{ email }, { voterId }] 
    });

    if (existingUser) {
      return res.status(400).render('register', {
        errors: [{ msg: 'User already exists with this email or voter ID' }],
        formData: req.body
      });
    }

    // Create new user
    const user = new User({
      email,
      password,
      name,
      voterId
    });

    await user.save();

    req.session.userId = user._id;
    res.redirect('/elections');
  } catch (error) {
    console.error(error);
    res.status(500).render('register', {
      errors: [{ msg: 'Server error during registration' }],
      formData: req.body
    });
  }
};

exports.login = async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).render('login', {
        errors: errors.array(),
        formData: req.body
      });
    }

    const { email, password } = req.body;

    // Find user
    const user = await User.findOne({ email });
    if (!user) {
      return res.status(400).render('login', {
        errors: [{ msg: 'Invalid credentials' }],
        formData: req.body
      });
    }

    // Check password
    const isMatch = await user.comparePassword(password);
    if (!isMatch) {
      return res.status(400).render('login', {
        errors: [{ msg: 'Invalid credentials' }],
        formData: req.body
      });
    }

    // Set session
    req.session.userId = user._id;
    req.session.userRole = user.role;

    if (user.role === 'admin') {
      res.redirect('/admin/dashboard');
    } else {
      res.redirect('/elections');
    }
  } catch (error) {
    console.error(error);
    res.status(500).render('login', {
      errors: [{ msg: 'Server error during login' }],
      formData: req.body
    });
  }
};

exports.logout = (req, res) => {
  req.session.destroy((err) => {
    if (err) {
      return res.redirect('/elections');
    }
    res.clearCookie('connect.sid');
    res.redirect('/');
  });
};