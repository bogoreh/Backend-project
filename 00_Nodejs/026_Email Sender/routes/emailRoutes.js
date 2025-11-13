const express = require('express');
const router = express.Router();
const emailService = require('../utils/emailService');
const path = require('path');

// Serve the main page
router.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, '../views/index.html'));
});

// Send email endpoint
router.post('/send-email', async (req, res) => {
  try {
    const { to, subject, message } = req.body;

    // Validation
    if (!to || !subject || !message) {
      return res.status(400).json({
        success: false,
        error: 'All fields are required'
      });
    }

    // Email validation regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(to)) {
      return res.status(400).json({
        success: false,
        error: 'Please enter a valid email address'
      });
    }

    const emailData = {
      to,
      subject,
      text: message,
      html: `<div style="font-family: Arial, sans-serif; padding: 20px;">
               <h2 style="color: #333;">${subject}</h2>
               <p style="line-height: 1.6; color: #666;">${message}</p>
               <hr style="border: none; border-top: 1px solid #eee;">
               <p style="color: #999; font-size: 12px;">Sent via Simple Email Sender</p>
             </div>`
    };

    const result = await emailService.sendEmail(emailData);

    if (result.success) {
      res.json({
        success: true,
        message: 'Email sent successfully!',
        messageId: result.messageId
      });
    } else {
      res.status(500).json({
        success: false,
        error: result.error
      });
    }
  } catch (error) {
    console.error('Route error:', error);
    res.status(500).json({
      success: false,
      error: 'Internal server error'
    });
  }
});

module.exports = router;