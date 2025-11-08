<?php
include '../config/database.php';
include '../includes/header.php';

$title = "Product List";
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM products ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-body">
        <?php if(count($products) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($product['product_code']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($product['category']); ?></span>
                            </td>
                            <td>
                                <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                            </td>
                            <td>
                                <span class="badge <?php echo $product['stock_quantity'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $product['stock_quantity']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $product['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted">Start by adding your first product.</p>
                <a href="create.php" class="btn btn-primary">Add Product</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>