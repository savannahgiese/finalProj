<?php
include 'password.php';
$mysqli = new mysqli("localhost", "root", $password, "sherlock");
if($mysqli -> connect_errno) {
  //$results['error_message'].= "Failed to connect to MySQL: (" . $mysqli -> connect_errno . ") " . $mysqli -> connect_error;
} else {
  //echo "Connection worked! <br>";
}?>