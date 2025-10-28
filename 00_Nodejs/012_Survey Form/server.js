const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = 3000;

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(express.static('public'));

// Path to store survey data
const dataPath = path.join(__dirname, 'data', 'surveys.json');

// Ensure data directory exists
const dataDir = path.join(__dirname, 'data');
if (!fs.existsSync(dataDir)) {
    fs.mkdirSync(dataDir);
}

// Initialize empty surveys file if it doesn't exist
if (!fs.existsSync(dataPath)) {
    fs.writeFileSync(dataPath, JSON.stringify([]));
}

// Routes
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Handle survey submission
app.post('/submit-survey', (req, res) => {
    try {
        const surveyData = req.body;
        
        // Add timestamp
        surveyData.timestamp = new Date().toISOString();
        
        // Read existing data
        const existingData = JSON.parse(fs.readFileSync(dataPath, 'utf8'));
        
        // Add new survey
        existingData.push(surveyData);
        
        // Write back to file
        fs.writeFileSync(dataPath, JSON.stringify(existingData, null, 2));
        
        res.json({ 
            success: true, 
            message: 'Thank you for completing the survey!' 
        });
    } catch (error) {
        console.error('Error saving survey:', error);
        res.status(500).json({ 
            success: false, 
            message: 'Error saving survey data' 
        });
    }
});

// Get all survey results (for viewing)
app.get('/survey-results', (req, res) => {
    try {
        const surveyData = JSON.parse(fs.readFileSync(dataPath, 'utf8'));
        res.json(surveyData);
    } catch (error) {
        console.error('Error reading survey data:', error);
        res.status(500).json({ error: 'Error reading survey data' });
    }
});

app.listen(PORT, () => {
    console.log(`Survey app running at http://localhost:${PORT}`);
    console.log(`Survey results available at http://localhost:${PORT}/survey-results`);
});