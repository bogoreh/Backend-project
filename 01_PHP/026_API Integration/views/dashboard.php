<?php require_once 'views/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="welcome-card">
            <h1 class="display-4 mb-3">API Integration Dashboard</h1>
            <p class="lead">Connect and display data from multiple third-party APIs in one unified interface</p>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="api-card weather-card">
            <div class="card-body text-center">
                <i class="fas fa-cloud-sun"></i>
                <h5 class="card-title">Weather API</h5>
                <p class="card-text">Get current weather information for any city worldwide</p>
                <form method="POST" class="d-flex gap-2">
                    <input type="text" name="city" class="form-control" placeholder="Enter city name" required>
                    <button type="submit" name="get_weather" class="btn btn-outline-light">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="api-card currency-card">
            <div class="card-body text-center">
                <i class="fas fa-money-bill-wave"></i>
                <h5 class="card-title">Currency API</h5>
                <p class="card-text">Real-time exchange rates and currency conversion</p>
                <form method="POST">
                    <div class="input-group">
                        <select name="base_currency" class="form-select">
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="JPY">JPY - Japanese Yen</option>
                        </select>
                        <button type="submit" name="get_currency" class="btn btn-outline-light">
                            Get Rates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="api-card news-card">
            <div class="card-body text-center">
                <i class="fas fa-newspaper"></i>
                <h5 class="card-title">News API</h5>
                <p class="card-text">Latest headlines and breaking news from global sources</p>
                <form method="POST">
                    <div class="input-group">
                        <select name="news_category" class="form-select">
                            <option value="general">General</option>
                            <option value="business">Business</option>
                            <option value="technology">Technology</option>
                            <option value="science">Science</option>
                            <option value="sports">Sports</option>
                        </select>
                        <button type="submit" name="get_news" class="btn btn-outline-light">
                            Get News
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($apiResults)): ?>
    <?php include 'views/api_results.php'; ?>
<?php endif; ?>

<?php require_once 'views/footer.php'; ?>