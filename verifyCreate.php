<?php 
session_start();
include 'database.php';
$username = mysqli_real_escape_string($mysqli, trim($_POST["username"]));
$password = mysqli_real_escape_string($mysqli, trim($_POST["password"]));

$results = array('status' => NULL, 'username' => NULL, 'error_message' => NULL);

if ($username == "" || $password == ""){
  if ($username == ""){       
    $results['error_message'].= "Please enter a username.<br>"; 
  }
  if ($password == ""){  
    $results['error_message'].= "Please enter a password.";
  }
}else{
  if (!($stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?"))) {
    //$results['error_message'].= "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if (!($stmt->bind_param("s",$username))){
    //$results['error_message'].= "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if (!$stmt->execute()) {
    //$results['error_message'].= "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  mysqli_stmt_store_result($stmt);
  $numRows = mysqli_stmt_num_rows($stmt);
  //$results['error_message'].= $numRows;
  if ($numRows > 0) {
    //check if the username already exists
    $results['error_message'].= "This username is already taken. <br>Please select a new one.";
  }else{
    //create user here
    //$results['error_message'].= "Creating a user";
    if (!($stmt = $mysqli->prepare("INSERT INTO users(username, password) 
        VALUES (?, md5(?))"))) {
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("ss", $username, $password)) {
      echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $pic = "defaultProfile.jpg";
    if (!($stmt = $mysqli->prepare("UPDATE `users` SET `picture` = (?) WHERE `username` = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!($stmt->bind_param("ss", $pic, $username))){
        echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $results['status'] = 'success';
    $results['username'] = $username;
    $_SESSION['username'] = $username;
  }
}
$mysqli->close();
echo json_encode($results);
?>
