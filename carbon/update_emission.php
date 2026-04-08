<?php
include 'db.php';

if($_POST){
    $activity_id = $_POST['activity_id'];
    $emission_factor = $_POST['emission_factor'];

    $sql = "UPDATE activities 
            SET emission_factor='$emission_factor'
            WHERE activity_id='$activity_id'";

    $conn->query($sql);

    echo "Emission factor updated successfully";
}
?>

<h2>Update Emission Factor</h2>

<form method="post">
    <input type="number" name="activity_id" placeholder="Activity ID" required>
    
    <input type="number" step="0.01" name="emission_factor" 
    placeholder="New Emission Factor" required>

    <button type="submit">Update</button>
</form>