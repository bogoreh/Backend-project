<?php
return [
    'weather' => [
        'base_url' => 'https://api.openweathermap.org/data/2.5/',
        'api_key' => 'ce1dea0f76a8f0b3bfe4dad260cbef10', // Replace with your actual key
        'endpoints' => [
            'current' => 'weather',
            'forecast' => 'forecast'
        ]
    ],
    
    'currency' => [
        'base_url' => 'https://api.exchangerate-api.com/v4/latest/',
        'endpoints' => [
            'rates' => ''
        ]
    ],
    
    'news' => [
        'base_url' => 'https://newsapi.org/v2/',
        'api_key' => 'c7b978500d934abfb08f58eebf9e545a', // Replace with your actual key
        'endpoints' => [
            'top_headlines' => 'top-headlines'
        ]
    ]
];