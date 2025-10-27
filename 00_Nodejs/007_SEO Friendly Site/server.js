const express = require('express');
const helmet = require('helmet');
const compression = require('compression');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware for SEO and security
app.use(helmet({
  contentSecurityPolicy: false, // For simplicity, adjust for production
}));
app.use(compression()); // Gzip compression
app.use(express.static('public')); // Serve static files

// Set view engine (simple HTML files)
app.engine('html', (filePath, options, callback) => {
  fs.readFile(filePath, 'utf-8', (err, content) => {
    if (err) return callback(err);
    
    // Simple template replacement
    let rendered = content
      .replace(/\{\{title\}\}/g, options.title || 'SEO Friendly Site')
      .replace(/\{\{description\}\}/g, options.description || 'A simple SEO-friendly website')
      .replace(/\{\{content\}\}/g, options.content || '');
    
    return callback(null, rendered);
  });
});
app.set('views', './views');
app.set('view engine', 'html');

// Routes with SEO-friendly URLs
app.get('/', (req, res) => {
  res.render('index', {
    title: 'Home - SEO Friendly Site',
    description: 'Welcome to our SEO optimized website built with Node.js',
    content: fs.readFileSync('./views/index.html', 'utf-8')
  });
});

app.get('/about', (req, res) => {
  res.render('layout', {
    title: 'About Us - SEO Friendly Site',
    description: 'Learn more about our company and mission',
    content: fs.readFileSync('./views/about.html', 'utf-8')
  });
});

app.get('/contact', (req, res) => {
  res.render('layout', {
    title: 'Contact Us - SEO Friendly Site',
    description: 'Get in touch with our team for any inquiries',
    content: fs.readFileSync('./views/contact.html', 'utf-8')
  });
});

// Sitemap route
app.get('/sitemap.xml', (req, res) => {
  res.sendFile(path.join(__dirname, 'sitemap.xml'));
});

// Robots.txt
app.get('/robots.txt', (req, res) => {
  res.type('text/plain');
  res.send(`User-agent: *\nAllow: /\nSitemap: ${req.protocol}://${req.get('host')}/sitemap.xml`);
});

// 404 handler
app.use((req, res) => {
  res.status(404).render('layout', {
    title: '404 - Page Not Found',
    description: 'The page you are looking for does not exist',
    content: '<h1>404 - Page Not Found</h1><p>The page you are looking for does not exist.</p>'
  });
});

app.listen(PORT, () => {
  console.log(`SEO-friendly site running on http://localhost:${PORT}`);
});