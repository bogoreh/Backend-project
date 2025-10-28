const mongoose = require('mongoose');

const leadSchema = new mongoose.Schema({
  name: {
    type: String,
    required: true,
    trim: true
  },
  email: {
    type: String,
    required: true,
    trim: true,
    lowercase: true
  },
  phone: {
    type: String,
    trim: true
  },
  company: {
    type: String,
    trim: true
  },
  status: {
    type: String,
    enum: ['new', 'contacted', 'qualified', 'proposal', 'closed'],
    default: 'new'
  },
  source: {
    type: String,
    enum: ['website', 'referral', 'social_media', 'cold_call', 'other'],
    default: 'website'
  },
  notes: {
    type: String,
    trim: true
  }
}, {
  timestamps: true
});

// Static method to get funnel statistics
leadSchema.statics.getFunnelStats = async function() {
  const stats = await this.aggregate([
    {
      $group: {
        _id: '$status',
        count: { $sum: 1 }
      }
    }
  ]);
  
  return stats.reduce((acc, curr) => {
    acc[curr._id] = curr.count;
    return acc;
  }, {});
};

module.exports = mongoose.model('Lead', leadSchema);