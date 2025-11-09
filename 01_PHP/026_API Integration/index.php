<?php
require_once 'config/config.php';
require_once 'config/api_config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $file = 'src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$apiResults = [];

try {
    $apiConfig = include 'config/api_config.php';

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Weather API
        if (isset($_POST['get_weather']) && !empty($_POST['city'])) {
            $weatherApi = new WeatherApi($apiConfig['weather']['api_key']);
            $apiResults['weather'] = $weatherApi->getCurrentWeather($_POST['city']);
        }
        
        // Currency API
        if (isset($_POST['get_currency'])) {
            $baseCurrency = $_POST['base_currency'] ?? 'USD';
            $currencyApi = new CurrencyApi();
            $apiResults['currency'] = $currencyApi->getExchangeRates($baseCurrency);
        }
        
        // News API
        if (isset($_POST['get_news'])) {
            $category = $_POST['news_category'] ?? 'general';
            $newsApi = new NewsApi($apiConfig['news']['api_key']);
            $apiResults['news'] = $newsApi->getTopHeadlines('us', $category);
        }
    }

} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    $apiResults['error'] = 'An error occurred while fetching data from APIs.';
}

// Display the dashboard
include 'views/dashboard.php';