<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Master Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-boxes me-2"></i>
                Product Master
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title">
                        <?php 
                        $page_title = "Product Management";
                        if(isset($title)) {
                            echo $title;
                        } else {
                            echo $page_title;
                        }
                        ?>
                    </h2>
                    <a href="create.php" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Add New Product
                    </a>
                </div>
            </div>
        </div>