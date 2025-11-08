<?php
include '../config/database.php';
include '../includes/header.php';

$title = "Edit Product";
$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Product ID not found.');
$error = "";

// Fetch existing product data
$query = "SELECT * FROM products WHERE id = ? LIMIT 0,1";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product){
    die('ERROR: Product not found.');
}

$product_code = $product['product_code'];
$product_name = $product['product_name'];
$description = $product['description'];
$category = $product['category'];
$price = $product['price'];
$stock_quantity = $product['stock_quantity'];

if($_POST){
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];

    if(empty($product_code) || empty($product_name) || empty($price)){
        $error = "Please fill in all required fields.";
    } else {
        try {
            $query = "UPDATE products 
                     SET product_code=:product_code, product_name=:product_name, 
                         description=:description, category=:category, 
                         price=:price, stock_quantity=:stock_quantity 
                     WHERE id=:id";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(":product_code", $product_code);
            $stmt->bindParam(":product_name", $product_name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":category", $category);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":stock_quantity", $stock_quantity);
            $stmt->bindParam(":id", $id);

            if($stmt->execute()){
                header("Location: index.php?message=Product updated successfully");
                exit();
            }
        } catch(PDOException $exception){
            $error = "Error: " . $exception->getMessage();
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Product
                </h5>
            </div>
            <div class="card-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_code" class="form-label">Product Code *</label>
                            <input type="text" class="form-control" id="product_code" name="product_code" 
                                   value="<?php echo htmlspecialchars($product_code); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="product_name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" 
                                   value="<?php echo htmlspecialchars($product_name); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" 
                                   value="<?php echo htmlspecialchars($category); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="price" name="price" 
                                       step="0.01" min="0" value="<?php echo htmlspecialchars($price); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                   min="0" value="<?php echo htmlspecialchars($stock_quantity); ?>">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>