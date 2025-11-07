<?php
include_once 'config/database.php';
include_once 'includes/header.php';

$database = new Database();
$db = $database->getConnection();

$query = isset($_GET['query']) ? $_GET['query'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$major = isset($_GET['major']) ? $_GET['major'] : '';

// Build SQL query
$sql = "SELECT * FROM alumni WHERE 1=1";
$params = [];

if (!empty($query)) {
    $sql .= " AND (name LIKE :query OR company LIKE :query OR position LIKE :query)";
    $params[':query'] = "%$query%";
}

if (!empty($year)) {
    $sql .= " AND graduation_year = :year";
    $params[':year'] = $year;
}

if (!empty($major)) {
    $sql .= " AND major = :major";
    $params[':major'] = $major;
}

$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$alumni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="search-results">
        <h2>Search Results</h2>
        
        <div class="results-info">
            <p>Found <?php echo count($alumni); ?> alumni matching your search</p>
            <a href="index.php" class="back-link">← New Search</a>
        </div>

        <?php if (count($alumni) > 0): ?>
            <div class="alumni-grid">
                <?php foreach ($alumni as $alumnus): ?>
                    <div class="alumni-card">
                        <div class="alumni-avatar">
                            <?php echo strtoupper(substr($alumnus['name'], 0, 1)); ?>
                        </div>
                        <div class="alumni-info">
                            <h3><?php echo htmlspecialchars($alumnus['name']); ?></h3>
                            <p class="graduation">Class of <?php echo htmlspecialchars($alumnus['graduation_year']); ?></p>
                            <p class="major"><?php echo htmlspecialchars($alumnus['major']); ?></p>
                            <p class="position"><?php echo htmlspecialchars($alumnus['position']); ?> at <?php echo htmlspecialchars($alumnus['company']); ?></p>
                            <p class="email"><?php echo htmlspecialchars($alumnus['email']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>No alumni found matching your search criteria.</p>
                <a href="index.php" class="btn-primary">Try Again</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>