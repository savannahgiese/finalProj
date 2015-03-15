<?php
error_reporting(-1);
ini_set('display_errors', 'On');
session_start();
include 'database.php';
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
		<h1>Sherlock Trivia</h1>
	</div>
    <?php 
    if(session_status() == PHP_SESSION_ACTIVE){
        if(isset($_SESSION['username']) && $_SESSION['username'] != NULL){
            $username = $_SESSION['username'];
            echo "<div id=\"navigation\"><ul>
    		<li><a href=\"http://savvyg.me/Final_Project/welcome.php\">Home</a></li>
    		<li><a href=\"http://savvyg.me/Final_Project/user.php\">User</a></li>
    		<li><a href=\"http://savvyg.me/Final_Project/play.php\">Play</a></li>
    		<li><a href=\"http://savvyg.me/Final_Project/logout.php\">Logout</a></li></ul></div>";
        }else {
            header("Location: http://savvyg.me/Final_Project/mainpage.php");
        }
    }
    
if (!($stmt = $mysqli->prepare("SELECT `nickname` FROM `users` WHERE username = ?"))) {
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
echo "<br><div id=\"nickname\"><h2>" . $nickname . "</h2></div>";

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
    
    echo "<div><img alt=\"Picture\" id=\"profilePic\" src=\"" . $userPic . "\"></div>";
    //echo "It worked!";
//else it will display the users picture
} else {
    echo "<div><img alt=\"Picture\" id=\"profilePic\" src=\"" . $userPic . "\"></div>";
}

?>
<form id="content">
<input type="submit" id="name" value="Change Nickname">
<input type="submit" id="pic" value="Change Picture">
</form>
<div id="matches">
<h2>Matches</h2>
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

if (!($stmt = $mysqli->prepare("SELECT `matchNum`, `id` FROM `match` WHERE `uid` = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("i", $userId))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_result($num, $id);

while ($stmt->fetch()) {
    //echo $tmp . "<br>";
    echo "<a href=\"http://savvyg.me/Final_Project/match.php?userId=" . $userId . "&mid=" . $id . "\">Match #" . $num . "</a><br>";
}
echo "</form><br><br>";
$mysqli->close();
?>
</div>
</div>
<script>
$(document).on("click", ":submit", function(event){ 
    event.preventDefault();
    var val = $(this).val();
    console.log(val);
    if (val == 'Change Picture'){
        var pic = prompt("Please enter a url:");
        console.log(pic);
        if((pic == '') || (pic == null)){
            //console.log(pic);
        }else{
            //console.log(pic);
            $.ajax({
                type: "POST",
                url: 'updateProfile.php',
                data: {
                pic: pic
                }
                //done function to show result if user got it right or wrong
            }).done(function(message){
                var result = JSON.parse(message);
                console.log(result.pic);
                var pic = result.pic;
                //$('#profilePic').load("src", result.pic);
                $('#profilePic').attr('src', pic);
                //location.reload(true);
            });
        }
    } else if (val == 'Change Nickname'){
        var name = prompt("Please enter a new nickname:");
        if((name == '') || (name == null)){
            console.log(name);
        }else{
            console.log(name);
            $.ajax({
                type: "POST",
                url: 'updateProfile.php',
                data: {
                name: name
                }
                //done function to show result if user got it right or wrong
            }).done(function(message){
                var result = JSON.parse(message);
                console.log(result.name);
                var name = result.name;
                //$('#profilePic').load("src", result.pic);
                $('#nickname').html("<h2>" + name + "</h2>");
                //location.reload(true);
            });
        }
    }
});
</script>
</body>
</html>


});