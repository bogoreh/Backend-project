<?php
class PhishingDetector {
    private $suspiciousKeywords = [
        'login', 'signin', 'verify', 'account', 'security', 'update',
        'banking', 'paypal', 'ebay', 'amazon', 'microsoft', 'apple',
        'confirm', 'validation', 'authentication', 'password'
    ];
    
    private $trustedDomains = [
        'google.com', 'microsoft.com', 'apple.com', 'amazon.com',
        'paypal.com', 'ebay.com', 'github.com', 'facebook.com'
    ];

    public function analyzeUrl($url) {
        $riskScore = 0;
        $reasons = [];
        
        // Basic URL validation
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'is_suspicious' => true,
                'risk_score' => 100,
                'reasons' => ['Invalid URL format']
            ];
        }
        
        $urlParts = parse_url($url);
        $host = strtolower($urlParts['host'] ?? '');
        $path = strtolower($urlParts['path'] ?? '');
        
        // Check for IP address instead of domain
        if ($this->isIpAddress($host)) {
            $riskScore += 20;
            $reasons[] = "Uses IP address instead of domain name";
        }
        
        // Check URL length
        if (strlen($url) > 75) {
            $riskScore += 15;
            $reasons[] = "URL is unusually long";
        }
        
        // Check for suspicious keywords in domain
        foreach ($this->suspiciousKeywords as $keyword) {
            if (strpos($host, $keyword) !== false) {
                $riskScore += 10;
                $reasons[] = "Contains suspicious keyword: '$keyword'";
                break;
            }
        }
        
        // Check for multiple subdomains
        $subdomainCount = count(explode('.', $host)) - 2;
        if ($subdomainCount > 2) {
            $riskScore += 15;
            $reasons[] = "Too many subdomains";
        }
        
        // Check for hyphens in domain
        if (substr_count($host, '-') > 3) {
            $riskScore += 10;
            $reasons[] = "Too many hyphens in domain";
        }
        
        // Check if domain is trusted
        $isTrusted = false;
        foreach ($this->trustedDomains as $trusted) {
            if (strpos($host, $trusted) !== false) {
                $isTrusted = true;
                $riskScore -= 20; // Reduce risk for trusted domains
                break;
            }
        }
        
        // Check for HTTPS
        if (($urlParts['scheme'] ?? '') !== 'https') {
            $riskScore += 25;
            $reasons[] = "Not using HTTPS (insecure connection)";
        }
        
        // Ensure risk score is between 0-100
        $riskScore = max(0, min(100, $riskScore));
        
        return [
            'is_suspicious' => $riskScore >= 50,
            'risk_score' => $riskScore,
            'reasons' => empty($reasons) ? ['No suspicious elements detected'] : $reasons
        ];
    }
    
    private function isIpAddress($host) {
        return preg_match('/^\d+\.\d+\.\d+\.\d+$/', $host);
    }
}
?>