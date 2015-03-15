<?php
session_start();
include 'database.php';

$username = $_SESSION['username'];
$results = array('status' => NULL, 'pic' => NULL, 'name' => NULL);

if(isset($_POST['pic'])){
    $pic = mysqli_real_escape_string($mysqli, trim($_POST['pic']));
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
}
if (isset($_POST['name'])){
    $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    if (!($stmt = $mysqli->prepare("UPDATE `users` SET `nickname` = (?) WHERE `username` = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!($stmt->bind_param("ss", $name, $username))){
        echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    //echo "<div id=\"profilePic\"><p><img alt=\"Picture\" id=\"picture\" src=\"" . $userPic . "\"></p>";
    $results['name'] = $name;
}
$mysqli->close();
echo json_encode($results);
?>