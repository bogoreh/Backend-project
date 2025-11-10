const express = require('express');
const multer = require('multer');
const Jimp = require('jimp');
const QrCode = require('qrcode-reader');
const path = require('path');
const fs = require('fs');

const router = express.Router();

// Configure multer for file uploads
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        const uploadDir = 'uploads/';
        if (!fs.existsSync(uploadDir)) {
            fs.mkdirSync(uploadDir);
        }
        cb(null, uploadDir);
    },
    filename: (req, file, cb) => {
        cb(null, `qr-${Date.now()}${path.extname(file.originalname)}`);
    }
});

const upload = multer({
    storage: storage,
    fileFilter: (req, file, cb) => {
        const allowedTypes = /jpeg|jpg|png|gif/;
        const extname = allowedTypes.test(path.extname(file.originalname).toLowerCase());
        const mimetype = allowedTypes.test(file.mimetype);
        
        if (mimetype && extname) {
            return cb(null, true);
        } else {
            cb(new Error('Only image files are allowed!'));
        }
    },
    limits: { fileSize: 5 * 1024 * 1024 } // 5MB limit
});

// Scan QR code from uploaded image
router.post('/scan', upload.single('qrcode'), async (req, res) => {
    try {
        if (!req.file) {
            return res.status(400).json({ 
                success: false, 
                message: 'No file uploaded' 
            });
        }

        const imagePath = req.file.path;

        // Read image with Jimp
        const image = await Jimp.read(fs.readFileSync(imagePath));
        
        // Create QR code reader instance
        const qr = new QrCode();
        
        // Decode the QR code
        const value = await new Promise((resolve, reject) => {
            qr.callback = (err, value) => {
                if (err) {
                    reject(err);
                } else {
                    resolve(value);
                }
            };
            qr.decode(image.bitmap);
        });

        // Clean up: delete the uploaded file
        fs.unlinkSync(imagePath);

        res.json({
            success: true,
            data: value.result,
            points: value.points
        });

    } catch (error) {
        // Clean up file if it exists
        if (req.file && fs.existsSync(req.file.path)) {
            fs.unlinkSync(req.file.path);
        }
        
        res.status(400).json({
            success: false,
            message: 'Failed to decode QR code',
            error: error.message
        });
    }
});

// Scan QR code from URL
router.post('/scan-url', async (req, res) => {
    try {
        const { imageUrl } = req.body;
        
        if (!imageUrl) {
            return res.status(400).json({
                success: false,
                message: 'Image URL is required'
            });
        }

        // Read image from URL
        const image = await Jimp.read(imageUrl);
        
        // Create QR code reader instance
        const qr = new QrCode();
        
        // Decode the QR code
        const value = await new Promise((resolve, reject) => {
            qr.callback = (err, value) => {
                if (err) {
                    reject(err);
                } else {
                    resolve(value);
                }
            };
            qr.decode(image.bitmap);
        });

        res.json({
            success: true,
            data: value.result,
            points: value.points
        });

    } catch (error) {
        res.status(400).json({
            success: false,
            message: 'Failed to decode QR code from URL',
            error: error.message
        });
    }
});

module.exports = router;