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
<div id="round"></div>
<button id="next">Play</button>
<form method='post' id='quiz_form'>
<?php 
$random = array();
for($num = 0; $num < 4; $num++){
    $q = rand(1,4);
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
$correct_answer = array();

for($q = 0; $q < 4; $q++){
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
        if($rowans["correct"] == 1){
            array_push($correct_answer, $rowans["answer"]);
        }
    }
}
//echo $questions[0];
//echo "<br>";
//echo $answers[0];
//echo $answers[1];
//echo $answers[2];
//echo $answers[3];
//echo "<br>";
//echo $correct_answer[0];
//var radioBtn = $('<input type="radio" name="rbtnCount" />');
//for(i=0; i<20; i++)
//{
//    radioBtn.appendTo('#target');
//}
?>
</form>
</div>
</div>
<script>

$(function () {
    var stuff = ['house', 'garden', 'sea', 'cat'],
        counter = 0;
    var answers = <?php echo json_encode($answers); ?>;
    var questions = <?php echo json_encode($questions); ?>;
    var correct = <?php echo json_encode($correct_answer); ?>;
    $('#next').click(function () {
        if (counter == 4){
             $('#next').html('Play Again');
             $('#next').click(function () {
                $('#next').html('Play');
                location.reload(true);
             });
        }else{
            console.log(counter);
            $('#next').html('Next');
            console.log(questions.length)
            console.log(questions[counter]);
            console.log(correct[counter]);
            console.log(answers[counter]);
            console.log(answers[counter + 1]);
            console.log(answers[counter + 2]);
            console.log(answers[counter + 3]);
            counter = counter + 1;
        }
    });
});

</script>
</body>
</html>
