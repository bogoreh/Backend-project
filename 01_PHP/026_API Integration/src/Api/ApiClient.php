<?php
class ApiClient {
    protected $baseUrl;
    protected $apiKey;
    protected $timeout = 30;

    public function __construct($baseUrl, $apiKey = null) {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    protected function makeRequest($endpoint, $params = []) {
        $url = $this->baseUrl . $endpoint;
        
        // Add API key if provided
        if ($this->apiKey) {
            $params['appid'] = $this->apiKey;
        }

        // Build query string
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'User-Agent: API-Integration-App/1.0'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }

        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
}