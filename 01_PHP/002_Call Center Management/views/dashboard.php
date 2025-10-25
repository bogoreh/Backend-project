<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Call Center Management System</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="index.php?page=agents">Agents</a></li>
                    <li><a href="index.php?page=calls">Calls</a></li>
                    <li><a href="index.php?page=customers">Customers</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Agents</h3>
                    <p><?php echo $totalAgents; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Calls</h3>
                    <p><?php echo $totalCalls; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Active Calls</h3>
                    <p><?php echo $activeCalls; ?></p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>