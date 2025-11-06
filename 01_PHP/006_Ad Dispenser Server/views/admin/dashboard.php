<?php
$adModel = new Ad();
$ads = $adModel->getAll();
$activeAds = $adModel->getActiveAds();

$totalImpressions = 0;
$totalClicks = 0;

foreach ($ads as $ad) {
    $totalImpressions += $ad['impressions'];
    $totalClicks += $ad['clicks'];
}
?>
<div class="dashboard">
    <h2>Dashboard</h2>
    
    <div class="stats">
        <div class="stat-card">
            <h3>Total Ads</h3>
            <p><?php echo count($ads); ?></p>
        </div>
        <div class="stat-card">
            <h3>Active Ads</h3>
            <p><?php echo count($activeAds); ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Impressions</h3>
            <p><?php echo $totalImpressions; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Clicks</h3>
            <p><?php echo $totalClicks; ?></p>
        </div>
    </div>
    
    <div class="recent-ads">
        <h3>Recent Ads</h3>
        <?php if (!empty($ads)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Impressions</th>
                        <th>Clicks</th>
                        <th>CTR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($ads, 0, 5) as $ad): ?>
                        <tr>
                            <td><?php echo $ad['title']; ?></td>
                            <td><?php echo $ad['type']; ?></td>
                            <td><?php echo $ad['status']; ?></td>
                            <td><?php echo $ad['impressions']; ?></td>
                            <td><?php echo $ad['clicks']; ?></td>
                            <td><?php echo $ad['impressions'] > 0 ? round(($ad['clicks'] / $ad['impressions']) * 100, 2) . '%' : '0%'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No ads created yet.</p>
        <?php endif; ?>
    </div>
</div>