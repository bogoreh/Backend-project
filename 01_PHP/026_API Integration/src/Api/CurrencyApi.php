<?php
require_once 'ApiClient.php';

class CurrencyApi extends ApiClient {
    public function __construct() {
        parent::__construct('https://api.exchangerate-api.com/v4/latest/');
    }

    public function getExchangeRates($baseCurrency = 'USD') {
        $response = $this->makeRequest($baseCurrency);
        return $this->formatCurrencyResponse($response);
    }

    private function formatCurrencyResponse($response) {
        if ($response['status'] !== 200 || !isset($response['data']['rates'])) {
            return ['error' => 'Failed to fetch currency data'];
        }

        $data = $response['data'];
        $popularCurrencies = ['USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD'];
        
        $rates = [];
        foreach ($popularCurrencies as $currency) {
            if (isset($data['rates'][$currency])) {
                $rates[$currency] = $data['rates'][$currency];
            }
        }

        return [
            'base_currency' => $data['base'],
            'rates' => $rates,
            'last_updated' => date('Y-m-d H:i:s', $data['time_last_updated'])
        ];
    }
}