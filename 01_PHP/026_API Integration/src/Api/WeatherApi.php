<?php
require_once 'ApiClient.php';

class WeatherApi extends ApiClient {
    public function __construct($apiKey) {
        parent::__construct('https://api.openweathermap.org/data/2.5/', $apiKey);
    }

    public function getCurrentWeather($city) {
        $params = [
            'q' => $city,
            'units' => 'metric'
        ];
        
        $response = $this->makeRequest('weather', $params);
        return $this->formatWeatherResponse($response);
    }

    private function formatWeatherResponse($response) {
        if ($response['status'] !== 200 || !isset($response['data']['weather'])) {
            return ['error' => 'Failed to fetch weather data'];
        }

        $data = $response['data'];
        return [
            'city' => $data['name'],
            'country' => $data['sys']['country'],
            'temperature' => round($data['main']['temp']),
            'description' => ucfirst($data['weather'][0]['description']),
            'humidity' => $data['main']['humidity'],
            'wind_speed' => $data['wind']['speed'],
            'icon' => $data['weather'][0]['icon']
        ];
    }
}