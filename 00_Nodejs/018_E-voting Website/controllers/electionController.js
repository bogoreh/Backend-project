const Election = require('../models/Election');
const Candidate = require('../models/Candidate');
const Vote = require('../models/Vote');
const User = require('../models/User');

exports.getActiveElections = async (req, res) => {
  try {
    const elections = await Election.find({
      status: 'active',
      isPublic: true
    }).populate('candidates');

    res.render('elections/active', { elections });
  } catch (error) {
    console.error(error);
    res.status(500).render('error', { message: 'Error fetching elections' });
  }
};

exports.getElectionDetails = async (req, res) => {
  try {
    const election = await Election.findById(req.params.id)
      .populate('candidates');

    if (!election) {
      return res.status(404).render('error', { message: 'Election not found' });
    }

    // Check if user has already voted
    const hasVoted = await Vote.findOne({
      voter: req.session.userId,
      election: req.params.id
    });

    res.render('elections/detail', { 
      election, 
      hasVoted: !!hasVoted,
      user: req.session.userId 
    });
  } catch (error) {
    console.error(error);
    res.status(500).render('error', { message: 'Error fetching election details' });
  }
};

exports.castVote = async (req, res) => {
  try {
    const { electionId, candidateId } = req.body;
    const userId = req.session.userId;

    // Verify election is active
    const election = await Election.findById(electionId);
    if (!election || election.status !== 'active') {
      return res.status(400).json({ 
        success: false, 
        message: 'Election is not active' 
      });
    }

    // Check if user has already voted
    const existingVote = await Vote.findOne({
      voter: userId,
      election: electionId
    });

    if (existingVote) {
      return res.status(400).json({ 
        success: false, 
        message: 'You have already voted in this election' 
      });
    }

    // Create vote
    const vote = new Vote({
      voter: userId,
      election: electionId,
      candidate: candidateId,
      ipAddress: req.ip
    });

    await vote.save();

    // Update candidate vote count
    await Candidate.findByIdAndUpdate(candidateId, {
      $inc: { votes: 1 }
    });

    // Update election total votes
    await Election.findByIdAndUpdate(electionId, {
      $inc: { totalVotes: 1 }
    });

    // Mark user as voted
    await User.findByIdAndUpdate(userId, {
      hasVoted: true
    });

    res.json({ 
      success: true, 
      message: 'Vote cast successfully' 
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({ 
      success: false, 
      message: 'Error casting vote' 
    });
  }
};