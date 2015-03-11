<?php
error_reporting(-1);
ini_set('display_errors', 'On');
session_start();
include 'database.php';
?>
<?php include 'database.php'?>
<html>
<head>
  <title>Sherlock Trivia</title>
  <link rel="stylesheet" href="website.css">
  <meta charset="UTF-8">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
		<div id=\"content\"><p><img alt=\"Picture\" id=\"picture\" src=\"http://savvyg.me/Final_Project/playAGame.gif\"></p>";
    }else {
        header("Location: http://savvyg.me/Final_Project/mainpage.php");
    }
}?>
<p id="text">If you click on the "Hide" button, I will disappear.</p>
<div id="next"></div>
<button id="next">Next</button>
<form method='post' id='quiz_form'>
<?php 
$random = array();
for($num = 0; $num < 20; $num++){
    $q = rand(1,100);
    if (in_array($q, $random)){
        $num--;
        //echo $q . " is already in the array. <br>";
    }else{
        array_push($random, $q);
        //echo $q . " is now in the array. <br>";
    }
}
$questions = array();
$answers = array();

for($q = 0; $q < 20; $q++){
    $tmp = $random[$q];
    //echo $tmp . "<br>";
    $sql="SELECT * FROM questions WHERE id = '".$tmp."'";
    $result=mysqli_query($mysqli,$sql);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC); 
    //echo "<label id='question'" . $row["question"] . "</label>";
    array_push($questions, $row["question"]);

    $anssql="SELECT * FROM answers WHERE qid ='".$tmp."' ORDER BY rand()";
    $resultans=mysqli_query($mysqli,$anssql);
    while($rowans=mysqli_fetch_array($resultans,MYSQLI_ASSOC)) {
        array_push($answers, $rowans["answer"]);
    }
}
$withComma = implode(", ", $answers);
for($num = 0; $num < 20; $num++){
    echo $questions[$num];
    echo "<br>";
    echo $withComma;
    echo "<br>";
}
?>
</form>
</div>
</div>
<script>

$(document).ready(function(){
   var currentPos = 0;

   $("#next").click(function()
   {
      currentPos++;
      printThings(currentPos);
   });
});

function printThings(maxLength)
{
   maxLength = maxLength > stuff.length ? stuff.length : maxLength;
   var stuff =["house","garden","sea","cat"];  
    for (var i=0; i<maxLength;i++)
    {
        console.log(stuff[i]);
    }  
}

</script>
</body>
</html>
