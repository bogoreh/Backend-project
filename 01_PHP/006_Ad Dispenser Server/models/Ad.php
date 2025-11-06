<?php
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../config/constants.php';

class Ad {
    private $db;
    private $table = 'ads';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, content, type, image_url, target_url, start_date, end_date, max_impressions, status) 
                VALUES (:title, :content, :type, :image_url, :target_url, :start_date, :end_date, :max_impressions, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getActiveAds() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = :status 
                AND start_date <= NOW() 
                AND (end_date IS NULL OR end_date >= NOW())
                AND (impressions < max_impressions OR max_impressions = 0)
                ORDER BY priority DESC, created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => AD_STATUS_ACTIVE]);
        return $stmt->fetchAll();
    }

    public function getRandomAd() {
        $ads = $this->getActiveAds();
        return !empty($ads) ? $ads[array_rand($ads)] : null;
    }

    public function incrementImpressions($adId) {
        $sql = "UPDATE {$this->table} SET impressions = impressions + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $adId]);
    }

    public function incrementClicks($adId) {
        $sql = "UPDATE {$this->table} SET clicks = clicks + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $adId]);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET 
                title = :title, content = :content, type = :type, image_url = :image_url, 
                target_url = :target_url, start_date = :start_date, end_date = :end_date, 
                max_impressions = :max_impressions, status = :status, priority = :priority
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>