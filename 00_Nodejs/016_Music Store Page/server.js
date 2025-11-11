const express = require('express');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(express.static('public'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Simple template engine for partials
app.engine('html', (filePath, options, callback) => {
    fs.readFile(filePath, 'utf8', (err, content) => {
        if (err) return callback(err);
        
        // Replace partial includes
        let rendered = content;
        const partialMatch = content.match(/\{\{> (.+?)\}\}/g);
        
        if (partialMatch) {
            partialMatch.forEach(match => {
                const partialName = match.replace('{{> ', '').replace('}}', '');
                const partialPath = path.join(__dirname, 'views', 'partials', partialName + '.html');
                
                try {
                    const partialContent = fs.readFileSync(partialPath, 'utf8');
                    rendered = rendered.replace(match, partialContent);
                } catch (error) {
                    console.error(`Error loading partial: ${partialName}`, error);
                }
            });
        }
        
        return callback(null, rendered);
    });
});

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'html');

// Routes
app.get('/', (req, res) => {
    res.render('index');
});

app.get('/api/products', (req, res) => {
    try {
        const productsData = fs.readFileSync(path.join(__dirname, 'data', 'products.json'), 'utf8');
        const products = JSON.parse(productsData);
        res.json(products);
    } catch (error) {
        console.error('Error reading products:', error);
        res.status(500).json({ error: 'Failed to load products' });
    }
});

app.get('/api/products/:id', (req, res) => {
    try {
        const productsData = fs.readFileSync(path.join(__dirname, 'data', 'products.json'), 'utf8');
        const products = JSON.parse(productsData);
        const product = products.find(p => p.id === parseInt(req.params.id));
        
        if (!product) {
            return res.status(404).json({ error: 'Product not found' });
        }
        
        res.json(product);
    } catch (error) {
        console.error('Error reading product:', error);
        res.status(500).json({ error: 'Failed to load product' });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`Music Store server running on http://localhost:${PORT}`);
});