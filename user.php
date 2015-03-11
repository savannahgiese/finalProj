<?php
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
        echo "<ul>
		<li><a href=\"http://savvyg.me/Final_Project/welcome.php\">Home</a></li>
		<li><a href=\"http://savvyg.me/Final_Project/user.php\">User</a></li>
		<li><a href=\"http://savvyg.me/Final_Project/play.php\">Play</a></li>
		<li><a href=\"http://savvyg.me/Final_Project/logout.php\">Logout</a></li></ul></div>
		<div id=\"content\"><p><img alt=\"Picture\" id=\"picture\" src=\"http://savvyg.me/Final_Project/tumblr_inline_ndcmyup8Cj1shcyx2.gif\"></p>
        <h2>Welcome, " . $_SESSION['username'] . "</h2></div>";
    }else {
        header("Location: http://savvyg.me/Final_Project/mainpage.php");
    }
}?>
</div>
</div>
</body>
</html>

