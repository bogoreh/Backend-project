<?php
require_once __DIR__ . '/../models/Ad.php';

class AdManager {
    private $adModel;

    public function __construct() {
        $this->adModel = new Ad();
    }

    public function displayAd() {
        $ad = $this->adModel->getRandomAd();
        
        if ($ad) {
            // Record impression
            $this->adModel->incrementImpressions($ad['id']);
            
            return $this->formatAd($ad);
        }
        
        return $this->getDefaultAd();
    }

    private function formatAd($ad) {
        switch ($ad['type']) {
            case AD_TYPE_BANNER:
                return $this->formatBannerAd($ad);
            case AD_TYPE_TEXT:
                return $this->formatTextAd($ad);
            case AD_TYPE_VIDEO:
                return $this->formatVideoAd($ad);
            default:
                return $this->formatTextAd($ad);
        }
    }

    private function formatBannerAd($ad) {
        $clickUrl = "click.php?ad_id={$ad['id']}&redirect=" . urlencode($ad['target_url']);
        
        return "
        <div class='ad-banner'>
            <a href='{$clickUrl}' target='_blank' onclick='trackClick({$ad['id']})'>
                <img src='{$ad['image_url']}' alt='{$ad['title']}' style='max-width: 100%; height: auto;'>
            </a>
            <div class='ad-title'>{$ad['title']}</div>
        </div>";
    }

    private function formatTextAd($ad) {
        $clickUrl = "click.php?ad_id={$ad['id']}&redirect=" . urlencode($ad['target_url']);
        
        return "
        <div class='ad-text'>
            <h4><a href='{$clickUrl}' target='_blank' onclick='trackClick({$ad['id']})'>{$ad['title']}</a></h4>
            <p>{$ad['content']}</p>
        </div>";
    }

    private function formatVideoAd($ad) {
        $clickUrl = "click.php?ad_id={$ad['id']}&redirect=" . urlencode($ad['target_url']);
        
        return "
        <div class='ad-video'>
            <video width='100%' controls>
                <source src='{$ad['image_url']}' type='video/mp4'>
                Your browser does not support the video tag.
            </video>
            <div class='ad-title'>
                <a href='{$clickUrl}' target='_blank' onclick='trackClick({$ad['id']})'>{$ad['title']}</a>
            </div>
        </div>";
    }

    private function getDefaultAd() {
        return "<div class='ad-default'>Sponsored Content</div>";
    }

    public function handleAdClick($adId) {
        $this->adModel->incrementClicks($adId);
    }
}
?>