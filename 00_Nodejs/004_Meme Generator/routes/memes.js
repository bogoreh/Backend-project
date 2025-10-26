const express = require('express');
const { createCanvas, loadImage } = require('canvas');
const axios = require('axios');
const fs = require('fs');
const path = require('path');

const router = express.Router();

// Predefined CDN meme templates
const memeTemplates = [
    {
        id: 1,
        name: 'Drake Hotline Bling',
        url: 'https://i.imgflip.com/30b1gx.jpg',
        width: 1200,
        height: 1200
    },
    {
        id: 2,
        name: 'Two Buttons',
        url: 'https://i.imgflip.com/1g8my4.jpg',
        width: 600,
        height: 908
    },
    {
        id: 3,
        name: 'Distracted Boyfriend',
        url: 'https://i.imgflip.com/1ur9b0.jpg',
        width: 1200,
        height: 800
    },
    {
        id: 4,
        name: 'Change My Mind',
        url: 'https://i.imgflip.com/24y43o.jpg',
        width: 1652,
        height: 930
    },
    {
        id: 5,
        name: 'Left Exit 12 Off Ramp',
        url: 'https://i.imgflip.com/22bdq6.jpg',
        width: 804,
        height: 767
    }
];

// Get available meme templates
router.get('/templates', (req, res) => {
    res.json(memeTemplates);
});

// Generate meme endpoint
router.post('/generate', async (req, res) => {
    try {
        const { templateId, topText, bottomText } = req.body;
        
        // Find the template
        const template = memeTemplates.find(t => t.id === parseInt(templateId));
        if (!template) {
            return res.status(404).json({ error: 'Template not found' });
        }

        // Download image from CDN
        const response = await axios({
            method: 'GET',
            url: template.url,
            responseType: 'arraybuffer'
        });

        // Load image
        const image = await loadImage(response.data);
        
        // Create canvas
        const canvas = createCanvas(template.width, template.height);
        const ctx = canvas.getContext('2d');

        // Draw the template image
        ctx.drawImage(image, 0, 0, template.width, template.height);

        // Configure text styling
        ctx.fillStyle = 'white';
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 3;
        ctx.textAlign = 'center';
        ctx.font = 'bold 48px Impact';

        // Add top text
        if (topText) {
            ctx.strokeText(topText.toUpperCase(), template.width / 2, 60);
            ctx.fillText(topText.toUpperCase(), template.width / 2, 60);
        }

        // Add bottom text
        if (bottomText) {
            ctx.strokeText(bottomText.toUpperCase(), template.width / 2, template.height - 30);
            ctx.fillText(bottomText.toUpperCase(), template.width / 2, template.height - 30);
        }

        // Generate filename
        const filename = `meme-${Date.now()}.png`;
        const filepath = path.join(__dirname, '../generated', filename);

        // Ensure generated directory exists
        if (!fs.existsSync(path.join(__dirname, '../generated'))) {
            fs.mkdirSync(path.join(__dirname, '../generated'));
        }

        // Save the image
        const buffer = canvas.toBuffer('image/png');
        fs.writeFileSync(filepath, buffer);

        // Send the image back
        res.set('Content-Type', 'image/png');
        res.send(buffer);

    } catch (error) {
        console.error('Error generating meme:', error);
        res.status(500).json({ error: 'Failed to generate meme' });
    }
});

module.exports = router;