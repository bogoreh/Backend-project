<div class="page-header">
    <h2>Agents Management</h2>
    <a href="index.php?page=agents&action=create" class="btn">Add New Agent</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agents as $agent): ?>
        <tr>
            <td><?php echo $agent['id']; ?></td>
            <td><?php echo htmlspecialchars($agent['name']); ?></td>
            <td><?php echo htmlspecialchars($agent['email']); ?></td>
            <td><?php echo htmlspecialchars($agent['phone']); ?></td>
            <td><?php echo htmlspecialchars($agent['department']); ?></td>
            <td><?php echo $agent['status']; ?></td>
            <td>
                <a href="index.php?page=agents&action=edit&id=<?php echo $agent['id']; ?>">Edit</a>
                <a href="index.php?page=agents&action=delete&id=<?php echo $agent['id']; ?>" 
                   onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>