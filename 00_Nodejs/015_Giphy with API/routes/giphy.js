const express = require('express');
const router = express.Router();
const giphyService = require('../services/giphyService');

// Search GIFs
router.get('/search', async (req, res) => {
  try {
    const { q, limit = 25, offset = 0 } = req.query;
    
    if (!q) {
      return res.status(400).json({ error: 'Query parameter "q" is required' });
    }

    const gifs = await giphyService.searchGifs(q, parseInt(limit), parseInt(offset));
    res.json(gifs);
  } catch (error) {
    console.error('Error searching GIFs:', error);
    res.status(500).json({ error: 'Failed to search GIFs' });
  }
});

// Get trending GIFs
router.get('/trending', async (req, res) => {
  try {
    const { limit = 25, offset = 0 } = req.query;
    const gifs = await giphyService.getTrendingGifs(parseInt(limit), parseInt(offset));
    res.json(gifs);
  } catch (error) {
    console.error('Error fetching trending GIFs:', error);
    res.status(500).json({ error: 'Failed to fetch trending GIFs' });
  }
});

// Get GIF by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const gif = await giphyService.getGifById(id);
    res.json(gif);
  } catch (error) {
    console.error('Error fetching GIF:', error);
    res.status(500).json({ error: 'Failed to fetch GIF' });
  }
});

module.exports = router;