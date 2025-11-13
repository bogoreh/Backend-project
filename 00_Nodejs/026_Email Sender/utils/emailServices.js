const nodemailer = require('nodemailer');
const emailConfig = require('../config/emailConfig');

class EmailService {
  constructor() {
    this.transporter = nodemailer.createTransporter(emailConfig);
  }

  async sendEmail(emailData) {
    try {
      const mailOptions = {
        from: emailConfig.auth.user,
        to: emailData.to,
        subject: emailData.subject,
        text: emailData.text,
        html: emailData.html
      };

      const result = await this.transporter.sendMail(mailOptions);
      return { success: true, messageId: result.messageId };
    } catch (error) {
      console.error('Email sending error:', error);
      return { success: false, error: error.message };
    }
  }

  // Verify transporter configuration
  async verifyConnection() {
    try {
      await this.transporter.verify();
      console.log('Email server connection verified');
      return true;
    } catch (error) {
      console.error('Email server connection failed:', error);
      return false;
    }
  }
}

module.exports = new EmailService();