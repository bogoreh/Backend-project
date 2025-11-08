<?php include 'header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-plus"></i> Add New Recipe</h2>
</div>

<form method="POST" class="recipe-form">
    <div class="form-group">
        <label for="title">Recipe Title</label>
        <input type="text" id="title" name="title" required class="form-control">
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="cooking_time">Cooking Time (minutes)</label>
            <input type="number" id="cooking_time" name="cooking_time" required class="form-control">
        </div>

        <div class="form-group">
            <label for="difficulty">Difficulty</label>
            <select id="difficulty" name="difficulty" required class="form-control">
                <option value="Easy">Easy</option>
                <option value="Medium" selected>Medium</option>
                <option value="Hard">Hard</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="ingredients">Ingredients (one per line)</label>
        <textarea id="ingredients" name="ingredients" rows="6" required class="form-control"></textarea>
    </div>

    <div class="form-group">
        <label for="instructions">Instructions</label>
        <textarea id="instructions" name="instructions" rows="8" required class="form-control"></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Recipe
        </button>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
    </div>
</form>

<?php include 'footer.php'; ?>