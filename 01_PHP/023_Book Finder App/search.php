<?php
session_start();
include_once 'config/database.php';
include_once 'models/Book.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$books = [];

if(!empty($query)) {
    $stmt = $book->search($query);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Book Finder</title>
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

    <div class="container mt-4">
        <!-- Search Header -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="index.php" class="btn btn-outline-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
                <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
                <p class="text-muted">Found <?php echo count($books); ?> result(s)</p>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="row">
            <?php if(!empty($query) && count($books) > 0): ?>
                <?php foreach($books as $book_item): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card book-card h-100">
                            <img src="<?php echo $book_item['cover_image'] ?: 'https://via.placeholder.com/200x300/4a5568/ffffff?text=No+Cover'; ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($book_item['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($book_item['title']); ?></h5>
                                <p class="card-text text-muted">by <?php echo htmlspecialchars($book_item['author']); ?></p>
                                <p class="card-text small">
                                    <strong>ISBN:</strong> <?php echo htmlspecialchars($book_item['isbn']); ?><br>
                                    <strong>Year:</strong> <?php echo htmlspecialchars($book_item['published_year']); ?><br>
                                    <strong>Category:</strong> <?php echo htmlspecialchars($book_item['category']); ?>
                                </p>
                                <?php if(!empty($book_item['description'])): ?>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($book_item['description'], 0, 100)); ?>...</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif(empty($query)): ?>
                <div class="col-12 text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Please enter a search query.
                    </div>
                </div>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-search"></i> No books found matching your search criteria.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 BookFinder App. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>