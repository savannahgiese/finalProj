<?php
session_start();
include 'database.php';

$username = $_SESSION['username'];
$pic = mysqli_real_escape_string($mysqli, trim($_POST["pic"]));
$results = array('status' => NULL, 'pic' => NULL);

if (!($stmt = $mysqli->prepare("UPDATE `users` SET `picture` = (?) WHERE `username` = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("ss", $pic, $username))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
//echo "<div id=\"profilePic\"><p><img alt=\"Picture\" id=\"picture\" src=\"" . $userPic . "\"></p>";
    
$results['pic'] = $pic;
$results['status'] = 'right';
$mysqli->close();
echo json_encode($results);
?>