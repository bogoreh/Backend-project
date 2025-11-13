const express = require('express');
const path = require('path');
const methodOverride = require('method-override');

// Import routes
const blogRoutes = require('./routes/blogRoutes');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(methodOverride('_method'));
app.use(express.static(path.join(__dirname, 'public')));

// Set view engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Routes
app.use('/', blogRoutes);

// Home route
app.get('/', (req, res) => {
  res.redirect('/posts');
});

app.listen(PORT, () => {
  console.log(`Blog system running on http://localhost:${PORT}`);
});