<?php
ini_set('display_errors', 'On');
include 'password.php';
include 'database.php';
session_start();
?>

<!DOCTYPE html>
<html>

<head>
<title>Sherlock Trivia</title>
<link rel="stylesheet" href="website.css">
<meta charset="UTF-8">
</head>
<body>
<div id="wrap">
    <div id="header">
		<h1>
			Sherlock Trivia
		</h1>
	</div>
	<div id="navigation">
<?php 
    if(session_status() == PHP_SESSION_ACTIVE){
    if(isset($_SESSION['username']) && $_SESSION['username'] != NULL){
    $username = $_SESSION['username'];
    if (!($stmt = $mysqli->prepare("SELECT `username` FROM `users` WHERE username = ?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!($stmt->bind_param("s", $username))){
        echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    
    $stmt->bind_result($name);
    while ($stmt->fetch()) {
        $nickname = $name;
        }
            echo "<ul>
	           <li><a href=\"http://savvyg.me/Final_Project/welcome.php\">Home</a></li>
	           <li><a href=\"http://savvyg.me/Final_Project/user.php\">User</a></li>
	           <li><a href=\"http://savvyg.me/Final_Project/play.php\">Play</a></li>
	           <li><a href=\"http://savvyg.me/Final_Project/logout.php\">Logout</a></li></ul></div>
	           <div id=\"content\"><p><img alt=\"Picture\" id=\"picture\" src=\"welcome.gif\"></p>
            <h2>Welcome, " . $nickname . "</h2></div>";
        }else{
            header("Location: http://savvyg.me/Final_Project/mainpage.php");
        }
    }
?>
</div>
</div>
</body>

</html>