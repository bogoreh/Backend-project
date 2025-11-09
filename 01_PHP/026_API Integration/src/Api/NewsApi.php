<?php
require_once 'ApiClient.php';

class NewsApi extends ApiClient {
    public function __construct($apiKey) {
        parent::__construct('https://newsapi.org/v2/', $apiKey);
    }

    public function getTopHeadlines($country = 'us', $category = 'general') {
        $params = [
            'country' => $country,
            'category' => $category,
            'pageSize' => 5
        ];
        
        $response = $this->makeRequest('top-headlines', $params);
        return $this->formatNewsResponse($response);
    }

    private function formatNewsResponse($response) {
        if ($response['status'] !== 200 || !isset($response['data']['articles'])) {
            return ['error' => 'Failed to fetch news data'];
        }

        $articles = [];
        foreach ($response['data']['articles'] as $article) {
            $articles[] = [
                'title' => $article['title'],
                'description' => $article['description'],
                'url' => $article['url'],
                'image' => $article['urlToImage'] ?: 'https://via.placeholder.com/300x150?text=No+Image',
                'source' => $article['source']['name'],
                'published_at' => date('M j, Y g:i A', strtotime($article['publishedAt']))
            ];
        }

        return [
            'total_results' => $response['data']['totalResults'],
            'articles' => $articles
        ];
    }
}