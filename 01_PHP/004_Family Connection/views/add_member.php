<?php
include_once '../config/database.php';
include_once '../models/Database.php';
include_once '../models/Family.php';
include_once '../models/Member.php';

$database = new Database();
$db = $database->getConnection();

$family = new Family($db);
$families = $family->read();

if($_POST){
    $member = new Member($db);
    
    $member->family_id = $_POST['family_id'];
    $member->name = $_POST['name'];
    $member->email = $_POST['email'];
    $member->phone = $_POST['phone'];
    $member->relationship = $_POST['relationship'];
    $member->birth_date = $_POST['birth_date'];
    
    if($member->create()){
        echo "<div class='alert success'>Member added successfully!</div>";
    } else{
        echo "<div class='alert error'>Unable to add member.</div>";
    }
}
?>

<h2>Add Family Member</h2>
<form method="post">
    <div class="form-group">
        <label>Family:</label>
        <select name="family_id" required>
            <option value="">Select Family</option>
            <?php while ($row = $families->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['family_name']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" required>
    </div>
    
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email">
    </div>
    
    <div class="form-group">
        <label>Phone:</label>
        <input type="text" name="phone">
    </div>
    
    <div class="form-group">
        <label>Relationship:</label>
        <select name="relationship" required>
            <option value="">Select Relationship</option>
            <option value="Parent">Parent</option>
            <option value="Child">Child</option>
            <option value="Spouse">Spouse</option>
            <option value="Sibling">Sibling</option>
            <option value="Grandparent">Grandparent</option>
            <option value="Grandchild">Grandchild</option>
            <option value="Other">Other</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Birth Date:</label>
        <input type="date" name="birth_date">
    </div>
    
    <button type="submit">Add Member</button>
</form>