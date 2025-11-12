const User = require('../models/User');

exports.requireAuth = async (req, res, next) => {
  if (!req.session.userId) {
    return res.redirect('/login');
  }

  try {
    const user = await User.findById(req.session.userId);
    if (!user) {
      return res.redirect('/login');
    }

    req.user = user;
    next();
  } catch (error) {
    console.error(error);
    res.redirect('/login');
  }
};

exports.redirectIfAuthenticated = (req, res, next) => {
  if (req.session.userId) {
    return res.redirect('/elections');
  }
  next();
};