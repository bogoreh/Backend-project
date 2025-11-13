const express = require('express');
const bodyParser = require('body-parser');
const nodemailer = require('nodemailer');
const path = require('path');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

// Email configuration
const emailConfig = {
  service: process.env.EMAIL_SERVICE || 'gmail',
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS
  }
};

const transporter = nodemailer.createTransporter(emailConfig);

// Routes
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'views', 'index.html'));
});

app.post('/send-email', async (req, res) => {
  try {
    const { to, subject, message } = req.body;

    // Validation
    if (!to || !subject || !message) {
      return res.status(400).json({
        success: false,
        error: 'All fields are required'
      });
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(to)) {
      return res.status(400).json({
        success: false,
        error: 'Please enter a valid email address'
      });
    }

    const mailOptions = {
      from: emailConfig.auth.user,
      to: to,
      subject: subject,
      text: message,
      html: `<div style="font-family: Arial, sans-serif; padding: 20px;">
               <h2 style="color: #333;">${subject}</h2>
               <p style="line-height: 1.6; color: #666;">${message}</p>
               <hr style="border: none; border-top: 1px solid #eee;">
               <p style="color: #999; font-size: 12px;">Sent via Simple Email Sender</p>
             </div>`
    };

    const result = await transporter.sendMail(mailOptions);
    
    res.json({
      success: true,
      message: 'Email sent successfully!',
      messageId: result.messageId
    });
  } catch (error) {
    console.error('Email sending error:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to send email. Please check your email configuration.'
    });
  }
});

// Create public directory and views if they don't exist
const fs = require('fs');
if (!fs.existsSync('public')) {
  fs.mkdirSync('public', { recursive: true });
}
if (!fs.existsSync('views')) {
  fs.mkdirSync('views', { recursive: true });
}

// Start server
app.listen(PORT, () => {
  console.log(`🚀 Server running on http://localhost:${PORT}`);
  console.log(`📧 Email sender app ready!`);
});