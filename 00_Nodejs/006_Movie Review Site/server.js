const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(express.static('public'));

// Path to reviews data file
const dataPath = path.join(__dirname, 'data', 'reviews.json');

// Helper function to read reviews
function readReviews() {
    try {
        const data = fs.readFileSync(dataPath, 'utf8');
        return JSON.parse(data);
    } catch (error) {
        // If file doesn't exist, return empty array
        return [];
    }
}

// Helper function to write reviews
function writeReviews(reviews) {
    const dir = path.dirname(dataPath);
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
    fs.writeFileSync(dataPath, JSON.stringify(reviews, null, 2));
}

// Routes
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'index.html'));
});

app.get('/add-review', (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'add-review.html'));
});

app.get('/reviews', (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'reviews.html'));
});

// API Routes
app.get('/api/reviews', (req, res) => {
    const reviews = readReviews();
    res.json(reviews);
});

app.post('/api/reviews', (req, res) => {
    const { movieTitle, reviewerName, rating, reviewText } = req.body;
    
    if (!movieTitle || !reviewerName || !rating || !reviewText) {
        return res.status(400).json({ error: 'All fields are required' });
    }

    const reviews = readReviews();
    const newReview = {
        id: Date.now().toString(),
        movieTitle,
        reviewerName,
        rating: parseInt(rating),
        reviewText,
        date: new Date().toISOString()
    };

    reviews.push(newReview);
    writeReviews(reviews);
    
    res.json({ success: true, review: newReview });
});

app.delete('/api/reviews/:id', (req, res) => {
    const reviewId = req.params.id;
    let reviews = readReviews();
    
    const initialLength = reviews.length;
    reviews = reviews.filter(review => review.id !== reviewId);
    
    if (reviews.length < initialLength) {
        writeReviews(reviews);
        res.json({ success: true });
    } else {
        res.status(404).json({ error: 'Review not found' });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`Movie Review Site running on http://localhost:${PORT}`);
});