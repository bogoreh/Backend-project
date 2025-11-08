<?php include 'header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-book-open"></i> My Recipes</h2>
</div>

<?php if($stmt->rowCount() > 0): ?>
    <div class="recipes-grid">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="recipe-card">
                <div class="recipe-header">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <span class="difficulty-badge <?php echo strtolower($row['difficulty']); ?>">
                        <?php echo $row['difficulty']; ?>
                    </span>
                </div>
                
                <div class="recipe-meta">
                    <span class="time"><i class="far fa-clock"></i> <?php echo $row['cooking_time']; ?> mins</span>
                </div>

                <div class="recipe-content">
                    <div class="ingredients">
                        <h4><i class="fas fa-list"></i> Ingredients</h4>
                        <p><?php echo nl2br(htmlspecialchars($row['ingredients'])); ?></p>
                    </div>
                    
                    <div class="instructions">
                        <h4><i class="fas fa-mortar-pestle"></i> Instructions</h4>
                        <p><?php echo nl2br(htmlspecialchars($row['instructions'])); ?></p>
                    </div>
                </div>

                <div class="recipe-actions">
                    <a href="index.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="index.php?action=delete&id=<?php echo $row['id']; ?>" 
                       class="btn btn-delete" 
                       onclick="return confirm('Are you sure you want to delete this recipe?')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-book fa-3x"></i>
        <h3>No Recipes Found</h3>
        <p>Start by adding your first recipe!</p>
        <a href="index.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Your First Recipe
        </a>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>