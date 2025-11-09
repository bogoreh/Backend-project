<?php
session_start();
include_once 'config/database.php';
include_once 'models/Book.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);

// Get all books for initial display
$stmt = $book->getAll();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Finder App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book"></i> BookFinder
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="hero-title">Discover Your Next Favorite Book</h1>
                    <p class="hero-subtitle">Search through thousands of books by title, author, or ISBN</p>
                    
                    <!-- Search Form -->
                    <form action="search.php" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control form-control-lg" 
                                   placeholder="Search by title, author, ISBN, or category..." 
                                   value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
                            <button class="btn btn-primary btn-lg" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="container mt-5">
        <h2 class="section-title">Featured Books</h2>
        <div class="row" id="books-grid">
            <?php if(count($books) > 0): ?>
                <?php foreach($books as $book_item): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card book-card h-100">
                            <img src="<?php echo $book_item['cover_image'] ?: 'https://via.placeholder.com/200x300/4a5568/ffffff?text=No+Cover'; ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($book_item['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($book_item['title']); ?></h5>
                                <p class="card-text text-muted">by <?php echo htmlspecialchars($book_item['author']); ?></p>
                                <p class="card-text small"><?php echo htmlspecialchars($book_item['published_year']); ?> • <?php echo htmlspecialchars($book_item['category']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No books found in the database.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 BookFinder App. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>