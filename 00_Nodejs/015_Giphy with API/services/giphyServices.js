const axios = require('axios');

class GiphyService {
  constructor() {
    this.apiKey = process.env.GIPHY_API_KEY;
    this.baseURL = 'https://api.giphy.com/v1/gifs';
  }

  async searchGifs(query, limit = 25, offset = 0) {
    try {
      const response = await axios.get(`${this.baseURL}/search`, {
        params: {
          api_key: this.apiKey,
          q: query,
          limit,
          offset,
          rating: 'g', // Content rating
          lang: 'en'
        }
      });
      return response.data;
    } catch (error) {
      console.error('Giphy API Error:', error.response?.data || error.message);
      throw error;
    }
  }

  async getTrendingGifs(limit = 25, offset = 0) {
    try {
      const response = await axios.get(`${this.baseURL}/trending`, {
        params: {
          api_key: this.apiKey,
          limit,
          offset,
          rating: 'g'
        }
      });
      return response.data;
    } catch (error) {
      console.error('Giphy API Error:', error.response?.data || error.message);
      throw error;
    }
  }

  async getGifById(id) {
    try {
      const response = await axios.get(`${this.baseURL}/${id}`, {
        params: {
          api_key: this.apiKey
        }
      });
      return response.data;
    } catch (error) {
      console.error('Giphy API Error:', error.response?.data || error.message);
      throw error;
    }
  }
}

module.exports = new GiphyService();