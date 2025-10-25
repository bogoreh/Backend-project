const express = require('express');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

// Serve static files from public directory
app.use(express.static(path.join(__dirname, 'public')));

// Routes
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.get('/api/info', (req, res) => {
    res.json({
        name: "John Doe",
        title: "Full Stack Developer",
        location: "New York, NY",
        email: "john.doe@email.com"
    });
});

// Start server
app.listen(PORT, () => {
    console.log(`Portfolio server running on http://localhost:${PORT}`);
});