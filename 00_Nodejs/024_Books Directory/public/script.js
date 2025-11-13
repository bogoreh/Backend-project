class BooksDirectory {
    constructor() {
        this.books = [];
        this.editingId = null;
        this.initializeApp();
    }

    async initializeApp() {
        await this.loadBooks();
        this.setupEventListeners();
        this.renderBooks();
        this.updateStats();
    }

    async loadBooks() {
        try {
            const response = await fetch('/api/books');
            this.books = await response.json();
        } catch (error) {
            console.error('Error loading books:', error);
            this.showMessage('Error loading books', 'error');
        }
    }

    setupEventListeners() {
        const bookForm = document.getElementById('bookForm');
        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        const cancelEdit = document.getElementById('cancelEdit');

        bookForm.addEventListener('submit', (e) => this.handleSubmit(e));
        searchInput.addEventListener('input', (e) => this.handleSearch(e));
        clearSearch.addEventListener('click', () => this.clearSearch());
        cancelEdit.addEventListener('click', () => this.cancelEdit());
    }

    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const bookData = Object.fromEntries(formData);

        try {
            if (this.editingId) {
                await this.updateBook(this.editingId, bookData);
                this.showMessage('Book updated successfully!', 'success');
            } else {
                await this.addBook(bookData);
                this.showMessage('Book added successfully!', 'success');
            }

            this.resetForm();
            await this.loadBooks();
            this.renderBooks();
            this.updateStats();
        } catch (error) {
            console.error('Error saving book:', error);
            this.showMessage('Error saving book', 'error');
        }
    }

    async addBook(bookData) {
        const response = await fetch('/api/books', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(bookData),
        });

        if (!response.ok) {
            throw new Error('Failed to add book');
        }
    }

    async updateBook(id, bookData) {
        const response = await fetch(`/api/books/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(bookData),
        });

        if (!response.ok) {
            throw new Error('Failed to update book');
        }
    }

    async deleteBook(id) {
        if (!confirm('Are you sure you want to delete this book?')) {
            return;
        }

        try {
            const response = await fetch(`/api/books/${id}`, {
                method: 'DELETE',
            });

            if (!response.ok) {
                throw new Error('Failed to delete book');
            }

            this.showMessage('Book deleted successfully!', 'success');
            await this.loadBooks();
            this.renderBooks();
            this.updateStats();
        } catch (error) {
            console.error('Error deleting book:', error);
            this.showMessage('Error deleting book', 'error');
        }
    }

    editBook(book) {
        this.editingId = book.id;
        document.getElementById('title').value = book.title;
        document.getElementById('author').value = book.author;
        document.getElementById('year').value = book.year;
        document.getElementById('genre').value = book.genre;
        document.getElementById('rating').value = book.rating;

        document.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-edit"></i> Update Book';
        document.getElementById('cancelEdit').style.display = 'block';

        // Scroll to form
        document.querySelector('.sidebar').scrollIntoView({ behavior: 'smooth' });
    }

    cancelEdit() {
        this.editingId = null;
        this.resetForm();
    }

    resetForm() {
        document.getElementById('bookForm').reset();
        document.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-save"></i> Add Book';
        document.getElementById('cancelEdit').style.display = 'none';
        this.editingId = null;
    }

    handleSearch(e) {
        const searchTerm = e.target.value.toLowerCase();
        this.renderBooks(searchTerm);
    }

    clearSearch() {
        document.getElementById('searchInput').value = '';
        this.renderBooks();
    }

    renderBooks(searchTerm = '') {
        const booksList = document.getElementById('booksList');
        const noBooks = document.getElementById('noBooks');

        let filteredBooks = this.books;
        if (searchTerm) {
            filteredBooks = this.books.filter(book => 
                book.title.toLowerCase().includes(searchTerm) ||
                book.author.toLowerCase().includes(searchTerm) ||
                book.genre.toLowerCase().includes(searchTerm)
            );
        }

        if (filteredBooks.length === 0) {
            booksList.style.display = 'none';
            noBooks.style.display = 'block';
            return;
        }

        booksList.style.display = 'grid';
        noBooks.style.display = 'none';

        booksList.innerHTML = filteredBooks.map(book => `
            <div class="book-card">
                <div class="book-header">
                    <div class="book-title">${this.escapeHtml(book.title)}</div>
                    <div class="book-actions">
                        <button class="btn btn-edit" onclick="app.editBook(${JSON.stringify(book).replace(/"/g, '&quot;')})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" onclick="app.deleteBook(${book.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="book-author">by ${this.escapeHtml(book.author)}</div>
                <div class="book-details">
                    <div class="book-detail">
                        <label>Year</label>
                        <span>${book.year}</span>
                    </div>
                    <div class="book-detail">
                        <label>Genre</label>
                        <span>${this.escapeHtml(book.genre)}</span>
                    </div>
                    <div class="book-detail">
                        <label>Rating</label>
                        <span class="rating">${this.renderStars(book.rating)}</span>
                    </div>
                    <div class="book-detail">
                        <label>ID</label>
                        <span>#${book.id}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    renderStars(rating) {
        const fullStars = Math.floor(rating);
        const halfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);

        return '★'.repeat(fullStars) + (halfStar ? '½' : '') + '☆'.repeat(emptyStars) + ` (${rating})`;
    }

    updateStats() {
        const totalBooks = this.books.length;
        const avgRating = totalBooks > 0 
            ? (this.books.reduce((sum, book) => sum + book.rating, 0) / totalBooks).toFixed(1)
            : '0.0';

        document.getElementById('totalBooks').textContent = totalBooks;
        document.getElementById('avgRating').textContent = avgRating;
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    showMessage(message, type) {
        // Create toast message
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}"></i>
            ${message}
        `;

        // Add styles for toast
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
}

// Add CSS animations for toast
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Initialize the application
const app = new BooksDirectory();