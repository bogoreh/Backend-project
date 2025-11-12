const express = require('express');
const router = express.Router();

// Mock data for testing
const mockElections = [
  {
    _id: '1',
    title: 'Student Council Election 2024',
    description: 'Elect your student council representatives for the academic year 2024-2025',
    startDate: new Date('2024-01-01'),
    endDate: new Date('2024-12-31'),
    status: 'active',
    candidates: [
      {
        _id: '1',
        name: 'John Smith',
        party: 'Student Progressive Alliance',
        bio: 'Dedicated to improving student life and academic resources.',
        photo: 'https://via.placeholder.com/100x100?text=JS'
      },
      {
        _id: '2',
        name: 'Sarah Johnson',
        party: 'Campus Unity Coalition',
        bio: 'Focused on creating inclusive campus events and activities.',
        photo: 'https://via.placeholder.com/100x100?text=SJ'
      }
    ]
  }
];

// Middleware to check if user is logged in
const requireAuth = (req, res, next) => {
  if (!req.session.userId) {
    return res.redirect('/login');
  }
  next();
};

router.use(requireAuth);

router.get('/', (req, res) => {
  res.render('elections/active', { elections: mockElections });
});

router.get('/:id', (req, res) => {
  const election = mockElections.find(e => e._id === req.params.id);
  if (!election) {
    return res.status(404).render('error', { 
      message: 'Election not found',
      error: {}
    });
  }
  
  res.render('elections/detail', { 
    election, 
    hasVoted: false, // Mock value for now
    user: req.session.userId 
  });
});

router.post('/vote', (req, res) => {
  // Mock vote casting
  res.json({ 
    success: true, 
    message: 'Vote cast successfully' 
  });
});

module.exports = router;