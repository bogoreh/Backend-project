<div class="row">
    <div class="col-12">
        <div class="results-section">
            <h3 class="mb-4"><i class="fas fa-chart-bar me-2"></i>API Integration Results</h3>
            
            <?php if (isset($apiResults['weather'])): ?>
            <div class="card mb-4">
                <div class="card-header text-white">
                    <i class="fas fa-temperature-low me-2"></i>Weather Information
                </div>
                <div class="card-body">
                    <?php if (!isset($apiResults['weather']['error'])): ?>
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <img src="http://openweathermap.org/img/w/<?php echo $apiResults['weather']['icon']; ?>.png" 
                                     alt="Weather icon" class="weather-icon mb-3">
                                <h4 class="text-accent"><?php echo $apiResults['weather']['temperature']; ?>°C</h4>
                            </div>
                            <div class="col-md-10">
                                <h5 class="text-primary"><?php echo $apiResults['weather']['city']; ?>, <?php echo $apiResults['weather']['country']; ?></h5>
                                <p class="lead text-secondary"><?php echo $apiResults['weather']['description']; ?></p>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <small class="text-muted">Humidity</small>
                                        <p class="h6"><?php echo $apiResults['weather']['humidity']; ?>%</p>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Wind Speed</small>
                                        <p class="h6"><?php echo $apiResults['weather']['wind_speed']; ?> m/s</p>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Condition</small>
                                        <p class="h6"><?php echo $apiResults['weather']['description']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $apiResults['weather']['error']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (isset($apiResults['currency'])): ?>
            <div class="card mb-4">
                <div class="card-header text-white">
                    <i class="fas fa-exchange-alt me-2"></i>Exchange Rates
                </div>
                <div class="card-body">
                    <?php if (!isset($apiResults['currency']['error'])): ?>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary">Base Currency: <?php echo $apiResults['currency']['base_currency']; ?></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">Last updated: <?php echo $apiResults['currency']['last_updated']; ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach ($apiResults['currency']['rates'] as $currency => $rate): ?>
                                <div class="col-md-2 mb-3">
                                    <div class="currency-card-small text-center p-3">
                                        <strong class="text-primary"><?php echo $currency; ?></strong>
                                        <div class="rate-value"><?php echo number_format($rate, 4); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $apiResults['currency']['error']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (isset($apiResults['news'])): ?>
            <div class="card mb-4">
                <div class="card-header text-white">
                    <i class="fas fa-newspaper me-2"></i>Top Headlines
                </div>
                <div class="card-body">
                    <?php if (!isset($apiResults['news']['error'])): ?>
                        <div class="row mb-3">
                            <div class="col-12">
                                <span class="text-muted">Total results: <?php echo $apiResults['news']['total_results']; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach ($apiResults['news']['articles'] as $article): ?>
                                <div class="col-lg-6 mb-4">
                                    <div class="news-card card h-100">
                                        <div class="row g-0 h-100">
                                            <div class="col-md-4">
                                                <img src="<?php echo $article['image']; ?>" 
                                                     class="img-fluid rounded-start h-100 w-100" 
                                                     style="object-fit: cover;" 
                                                     alt="<?php echo htmlspecialchars($article['title']); ?>"
                                                     onerror="this.src='https://via.placeholder.com/300x150/334155/94a3b8?text=News+Image'">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body d-flex flex-column h-100">
                                                    <h6 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h6>
                                                    <p class="card-text small text-muted flex-grow-1">
                                                        <?php echo htmlspecialchars(substr($article['description'] ?? 'No description available', 0, 100)); ?>...
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <small class="text-muted"><?php echo $article['source']; ?></small>
                                                        <small class="text-muted"><?php echo $article['published_at']; ?></small>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="<?php echo $article['url']; ?>" target="_blank" class="btn btn-sm btn-outline-light w-100">
                                                            Read Full Article <i class="fas fa-arrow-right ms-1"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $apiResults['news']['error']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>