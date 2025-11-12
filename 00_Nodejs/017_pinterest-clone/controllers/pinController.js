const Pin = require('../models/Pin');
const Board = require('../models/Board');
const { validationResult } = require('express-validator');

exports.createPin = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { title, description, link, boardId, tags } = req.body;

    // Check if board exists and user owns it
    const board = await Board.findOne({ _id: boardId, owner: req.user.id });
    if (!board) {
      return res.status(404).json({ error: 'Board not found' });
    }

    const pin = new Pin({
      title,
      description,
      link,
      board: boardId,
      owner: req.user.id,
      tags: tags ? tags.split(',').map(tag => tag.trim()) : [],
      image: {
        url: req.file.path,
        publicId: req.file.filename
      }
    });

    await pin.save();

    // Add pin to board
    board.pins.push(pin._id);
    await board.save();

    await pin.populate('owner', 'username profilePicture');
    await pin.populate('board', 'name');

    res.status(201).json({ pin });
  } catch (error) {
    next(error);
  }
};

exports.getPins = async (req, res, next) => {
  try {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 20;
    const skip = (page - 1) * limit;

    const pins = await Pin.find()
      .populate('owner', 'username profilePicture')
      .populate('board', 'name')
      .sort({ createdAt: -1 })
      .skip(skip)
      .limit(limit);

    const total = await Pin.countDocuments();

    res.json({
      pins,
      currentPage: page,
      totalPages: Math.ceil(total / limit),
      totalPins: total
    });
  } catch (error) {
    next(error);
  }
};

exports.getPin = async (req, res, next) => {
  try {
    const pin = await Pin.findById(req.params.id)
      .populate('owner', 'username profilePicture')
      .populate('board', 'name')
      .populate('comments.user', 'username profilePicture');

    if (!pin) {
      return res.status(404).json({ error: 'Pin not found' });
    }

    res.json({ pin });
  } catch (error) {
    next(error);
  }
};

exports.likePin = async (req, res, next) => {
  try {
    const pin = await Pin.findById(req.params.id);
    if (!pin) {
      return res.status(404).json({ error: 'Pin not found' });
    }

    const hasLiked = pin.likes.includes(req.user.id);
    
    if (hasLiked) {
      pin.likes.pull(req.user.id);
    } else {
      pin.likes.push(req.user.id);
    }

    await pin.save();
    res.json({ likes: pin.likes.length, liked: !hasLiked });
  } catch (error) {
    next(error);
  }
};

exports.savePin = async (req, res, next) => {
  try {
    const user = await User.findById(req.user.id);
    const pin = await Pin.findById(req.params.id);

    if (!pin) {
      return res.status(404).json({ error: 'Pin not found' });
    }

    const hasSaved = user.savedPins.includes(pin._id);
    
    if (hasSaved) {
      user.savedPins.pull(pin._id);
    } else {
      user.savedPins.push(pin._id);
    }

    await user.save();
    res.json({ saved: !hasSaved });
  } catch (error) {
    next(error);
  }
};

exports.addComment = async (req, res, next) => {
  try {
    const { text } = req.body;
    const pin = await Pin.findById(req.params.id);

    if (!pin) {
      return res.status(404).json({ error: 'Pin not found' });
    }

    pin.comments.push({
      user: req.user.id,
      text
    });

    await pin.save();
    await pin.populate('comments.user', 'username profilePicture');

    const newComment = pin.comments[pin.comments.length - 1];
    res.status(201).json({ comment: newComment });
  } catch (error) {
    next(error);
  }
};