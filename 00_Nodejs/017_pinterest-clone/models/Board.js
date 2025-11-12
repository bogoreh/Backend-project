const mongoose = require('mongoose');

const boardSchema = new mongoose.Schema({
  name: {
    type: String,
    required: true,
    trim: true,
    maxlength: 50
  },
  description: {
    type: String,
    maxlength: 200
  },
  owner: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: true
  },
  pins: [{
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Pin'
  }],
  isPrivate: {
    type: Boolean,
    default: false
  },
  coverImage: {
    url: String,
    publicId: String
  },
  collaborators: [{
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User'
  }]
}, {
  timestamps: true
});

module.exports = mongoose.model('Board', boardSchema);