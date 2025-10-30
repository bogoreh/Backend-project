<?php
include_once '../config/database.php';
include_once '../models/Database.php';
include_once '../models/Family.php';
include_once '../models/Member.php';

$database = new Database();
$db = $database->getConnection();

$family = new Family($db);
$member = new Member($db);

$families = $family->read();
$selected_family = null;
$family_members = [];

if(isset($_GET['family_id']) && $_GET['family_id'] != '') {
    $selected_family = $_GET['family_id'];
    $family_members = $member->readByFamily($selected_family);
}
?>

<h2>View Family Members</h2>

<div class="form-group">
    <label>Select Family:</label>
    <select onchange="if(this.value) window.location.href='index.php?action=view_family&family_id='+this.value">
        <option value="">Select a Family</option>
        <?php 
        $families = $family->read();
        while ($row = $families->fetch(PDO::FETCH_ASSOBJ)): 
        ?>
            <option value="<?php echo $row->id; ?>" <?php echo ($selected_family == $row->id) ? 'selected' : ''; ?>>
                <?php echo $row->family_name; ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

<?php if($selected_family): ?>
    <h3>Family Members</h3>
    <?php if($family_members->rowCount() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Relationship</th>
                    <th>Birth Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $family_members->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['relationship']; ?></td>
                        <td><?php echo $row['birth_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No members found for this family.</p>
    <?php endif; ?>
<?php endif; ?>