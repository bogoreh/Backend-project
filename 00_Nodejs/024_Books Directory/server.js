const express = require('express');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(express.json());
app.use(express.static('public'));
app.use(express.urlencoded({ extended: true }));

// Path to books data file
const booksFile = path.join(__dirname, 'data', 'books.json');

// Helper function to read books
const readBooks = () => {
    try {
        const data = fs.readFileSync(booksFile, 'utf8');
        return JSON.parse(data);
    } catch (error) {
        return [];
    }
};

// Helper function to write books
const writeBooks = (books) => {
    try {
        fs.writeFileSync(booksFile, JSON.stringify(books, null, 2));
        return true;
    } catch (error) {
        return false;
    }
};

// Initialize books file with sample data if empty
const initializeBooks = () => {
    const books = readBooks();
    if (books.length === 0) {
        const sampleBooks = [
            {
                id: 1,
                title: "The Great Gatsby",
                author: "F. Scott Fitzgerald",
                year: 1925,
                genre: "Fiction",
                rating: 4.5
            },
            {
                id: 2,
                title: "To Kill a Mockingbird",
                author: "Harper Lee",
                year: 1960,
                genre: "Fiction",
                rating: 4.8
            },
            {
                id: 3,
                title: "1984",
                author: "George Orwell",
                year: 1949,
                genre: "Dystopian",
                rating: 4.7
            }
        ];
        writeBooks(sampleBooks);
    }
};

// Routes

// Get all books
app.get('/api/books', (req, res) => {
    const books = readBooks();
    res.json(books);
});

// Get book by ID
app.get('/api/books/:id', (req, res) => {
    const books = readBooks();
    const book = books.find(b => b.id === parseInt(req.params.id));
    
    if (!book) {
        return res.status(404).json({ error: 'Book not found' });
    }
    
    res.json(book);
});

// Add new book
app.post('/api/books', (req, res) => {
    const { title, author, year, genre, rating } = req.body;
    
    if (!title || !author) {
        return res.status(400).json({ error: 'Title and author are required' });
    }
    
    const books = readBooks();
    const newBook = {
        id: books.length > 0 ? Math.max(...books.map(b => b.id)) + 1 : 1,
        title,
        author,
        year: parseInt(year) || new Date().getFullYear(),
        genre: genre || 'Unknown',
        rating: parseFloat(rating) || 0
    };
    
    books.push(newBook);
    
    if (writeBooks(books)) {
        res.status(201).json(newBook);
    } else {
        res.status(500).json({ error: 'Failed to save book' });
    }
});

// Update book
app.put('/api/books/:id', (req, res) => {
    const books = readBooks();
    const bookIndex = books.findIndex(b => b.id === parseInt(req.params.id));
    
    if (bookIndex === -1) {
        return res.status(404).json({ error: 'Book not found' });
    }
    
    const { title, author, year, genre, rating } = req.body;
    
    books[bookIndex] = {
        ...books[bookIndex],
        title: title || books[bookIndex].title,
        author: author || books[bookIndex].author,
        year: year ? parseInt(year) : books[bookIndex].year,
        genre: genre || books[bookIndex].genre,
        rating: rating ? parseFloat(rating) : books[bookIndex].rating
    };
    
    if (writeBooks(books)) {
        res.json(books[bookIndex]);
    } else {
        res.status(500).json({ error: 'Failed to update book' });
    }
});

// Delete book
app.delete('/api/books/:id', (req, res) => {
    const books = readBooks();
    const bookIndex = books.findIndex(b => b.id === parseInt(req.params.id));
    
    if (bookIndex === -1) {
        return res.status(404).json({ error: 'Book not found' });
    }
    
    const deletedBook = books.splice(bookIndex, 1)[0];
    
    if (writeBooks(books)) {
        res.json({ message: 'Book deleted successfully', book: deletedBook });
    } else {
        res.status(500).json({ error: 'Failed to delete book' });
    }
});

// Serve main page
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Initialize and start server
initializeBooks();
app.listen(PORT, () => {
    console.log(`Books Directory server running on http://localhost:${PORT}`);
});