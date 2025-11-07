<?php include_once 'includes/header.php'; ?>

<div class="container">
    <div class="search-engine">
        <h1>Alumni Search Engine</h1>
        <p class="subtitle">Find and connect with fellow graduates</p>
        
        <form action="search.php" method="GET" class="search-form">
            <div class="search-box">
                <input type="text" name="query" placeholder="Search by name, graduation year, major, or company..." 
                       value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            
            <div class="filters">
                <div class="filter-group">
                    <label>Graduation Year:</label>
                    <select name="year">
                        <option value="">Any Year</option>
                        <?php
                        $current_year = date('Y');
                        for ($year = $current_year; $year >= 1970; $year--) {
                            $selected = (isset($_GET['year']) && $_GET['year'] == $year) ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Major:</label>
                    <select name="major">
                        <option value="">Any Major</option>
                        <option value="Computer Science" <?php echo (isset($_GET['major']) && $_GET['major'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                        <option value="Business" <?php echo (isset($_GET['major']) && $_GET['major'] == 'Business') ? 'selected' : ''; ?>>Business</option>
                        <option value="Engineering" <?php echo (isset($_GET['major']) && $_GET['major'] == 'Engineering') ? 'selected' : ''; ?>>Engineering</option>
                        <option value="Arts" <?php echo (isset($_GET['major']) && $_GET['major'] == 'Arts') ? 'selected' : ''; ?>>Arts</option>
                        <option value="Science" <?php echo (isset($_GET['major']) && $_GET['major'] == 'Science') ? 'selected' : ''; ?>>Science</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>