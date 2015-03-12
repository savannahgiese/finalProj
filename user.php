<?php
error_reporting(-1);
ini_set('display_errors', 'On');
session_start();
include 'database.php';
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>

<head>
  <title>Sherlock Trivia</title>
  <link rel="stylesheet" href="website.css">
  <meta charset="UTF-8">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
    		<li><a href=\"http://savvyg.me/Final_Project/logout.php\">Logout</a></li></ul></div>";
        }else {
            header("Location: http://savvyg.me/Final_Project/mainpage.php");
        }
    }?>
<form id="content">
<?php 
if (!($stmt = $mysqli->prepare("SELECT `picture` FROM `users` WHERE username = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("s", $username))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_result($pic);
while ($stmt->fetch()) {
    $userPic = $pic;
}

//if no picture is set, default will be set
if ($userPic == NULL){
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
    
    echo "<div id=\"pic\"><p><img alt=\"Picture\" id=\"profilePic\" src=\"" . $userPic . "\"></p>";
    //echo "It worked!";
//else it will display the users picture
} else {
    echo "<div id=\"pic\"><p><img alt=\"Picture\" id=\"profilePic\" src=\"" . $userPic . "\"></p>";
}

?>
</form>
<button id="pic" onClick="updateProf()">Change Picture</button>
<form id="results">
    <h2 align='left'>Matches</h2>
<?php
if (!($stmt = $mysqli->prepare("SELECT `id` FROM `users` WHERE `username` = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("s", $username))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_result($id);
while ($stmt->fetch()) {
    $userId = $id;
}

//echo $userId . "<br>";

if (!($stmt = $mysqli->prepare("SELECT `id` FROM `match` WHERE `uid` = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("i", $userId))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_result($id);

echo "<div align='left'>";

while ($stmt->fetch()) {
    echo "<input class=\"matches\" type=\"submit\" value=\"Match #" . $id . "\"><br>";
}

echo "</div>";

$mysqli->close();
?>
<div id="filter" align='left'>
    <input class="matches" type="submit" value=""></button>
</div>
</form>
</div>
</div>
<script>
function updateProf() {
    var pic = prompt("Please enter a url:");
    console.log(pic);
    if (pic != "") {
        console.log(pic);
        $.ajax({
            type: "POST",
            url: 'updateProfile.php',
            data: {
            pic: pic
            }
            //done function to show result if user got it right or wrong
        }).done(function(message){
            var result = JSON.parse(message);
            if (result.status == 'right') {
              console.log(result.pic);
              $('#pic').load("src", result.pic); 
            }else if(result.status == 'wrong'){
              console.log(result.pic);
              $('#pic').load("src", result.pic); 
            }
        });
    }
}
</script>
<script>
$(document).ready(function() {
    $("form").submit(function(event) { 
        event.preventDefault();
        var val = $("input[type=submit][clicked=true]").val();
        console.log(val);
        // DO WORK
    });
});
</script>
<script>
$("form input[type=submit]").click(function() {
    $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
    $(this).attr("clicked", "true");
});
</script>
</body>
</html>
