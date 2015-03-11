<?php 
session_start();
include 'database.php';
$username = mysqli_real_escape_string($mysqli, trim($_POST["username"]));
$password = mysqli_real_escape_string($mysqli, trim($_POST["password"]));
$results = array('status' => NULL, 'username' => NULL, 'error_message' => NULL);
//$results['error_message'] = $username;

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
  if ($numRows == 0) {
    //check if the username exists
    $results['error_message'].= "Username does not exist.";
  }else{
    //if username does exist, login user here
    //$results['error_message'].= "Login";
    if (!($stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = md5(?)"))) {
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("ss", $username, $password)) {
      echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    mysqli_stmt_store_result($stmt);
    $numRows = mysqli_stmt_num_rows($stmt);
    if ($numRows == 1) {
      $results['status'] = 'success';
      $results['username'] = $username;
      $_SESSION['username'] = $username;
    } else {
      //check if the username exists
      $results['error_message'].= "Password is incorrect.";
    }
  }
}
$mysqli->close();
echo json_encode($results);
?>
