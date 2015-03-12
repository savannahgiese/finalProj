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
    
<form id="content">
<div id="score"></div>
<div id="questionNum"></div>
<div id="question"></div>
<div name="answers" id="answer1"></div>
<div name="answers" id="answer2"></div>
<div name="answers" id="answer3"></div>
<div name="answers" id="answer4"></div>
</form>
<button id="next">Play</button>
<?php 
$username = $_SESSION['username'];
$random = array();
for($num = 0; $num < 20; $num++){
    $q = rand(1, 20);
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
    $sql = "SELECT * FROM `questions` WHERE `id` = '".$tmp."'";
    $result = mysqli_query($mysqli,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC); 
    //echo "<label id='question'" . $row["question"] . "</label>";
    array_push($questions, $row["question"]);

    $anssql = "SELECT * FROM `answers` WHERE `qid` ='".$tmp."' ORDER BY rand()";
    $resultans = mysqli_query($mysqli,$anssql);
    while($rowans = mysqli_fetch_array($resultans,MYSQLI_ASSOC)) {
        array_push($answers, $rowans["answer"]);
    }
}

if (!($stmt = $mysqli->prepare("SELECT `id` FROM `users` WHERE username = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("s", $username))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_result($id);
$userId = 0;
while ($stmt->fetch()) {
    $userId = $id;
}

$result = $mysqli->query("SELECT COUNT(*) FROM `match`");
$row = $result->fetch_row();
//echo '#: ', $row[0];
$match = $row[0] + 1;

//checks if there are any matches, and if not, creates a new one
if ($row[0] == 0) {
    //echo "Creating a new match";
    if (!$stmt = $mysqli->prepare("INSERT INTO `match` (`id`, `uid`) VALUES (?, ?)")){
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("ii", $match, $userId)) {
      echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //var_dump($stmt);
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
} else {
    //echo $userId;
    if (!$stmt = $mysqli->prepare("INSERT INTO `match` (`id`, `uid`) VALUES (?, ?)")){
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("ii", $match, $userId)) {
      echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    ////var_dump($stmt);
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

$mysqli->close();
?>
</div>
</div>
<script>
$(function () {
    var qNum = 1;
    var radioBtn;
    var qcounter = 0;
    var acounter = 0;
    var cur = 0;
    var question;
    var answer;
    var correct = 0;
    var questionId = <?php echo json_encode($random);?>;
    var answers = <?php echo json_encode($answers);?>;
    var questions = <?php echo json_encode($questions);?>;
    var username = <?php echo json_encode($username);?>;
    var match = <?php echo json_encode($match);?>;
    $('#next').click(function () {
        //console.log(qcounter);
        if (qcounter == 19){
            $('#next').click(function () {
                if (correct > 12){
                    $("#score").html(correct + " hooray!");
                    $('#picture').attr("src", "win.gif");
                } else {
                    $("#score").html(correct + " boo!");
                    $('#picture').attr("src", "insult.gif");
                }
                $('#question').remove();
                $('#answer1').remove();
                $('#answer2').remove();
                $('#answer3').remove();
                $('#answer4').remove();
                $('#questionNum').remove();
                //changes the button to 'play again' when 20 questions have finished
                $('#next').html('Play again');
                $('#next').show();
                if ($('#next').click(function () {
                    location.reload(true);
                }));
            });
        }
        $('#picture').attr("src", "think.gif");
        //console.log("counter = " + qcounter);
        $('#next').hide();
        //console.log(questions.length)
        $('#questionNum').html('Question #' + qNum);
        $('#question').html("<p>" + questions[qcounter] + "</p>");
        //console.log("questions = " + questions[qcounter]);
        $('#answer1').html("<input type=\"radio\" name=\"answer\" value=\"" + answers[acounter] + "\">" + answers[acounter] + "<br>");
        //console.log("answer  1=" + answers[acounter]);
        $('#answer2').html("<input type=\"radio\" name=\"answer\" value=\"" + answers[acounter + 1] + "\">" + answers[acounter + 1] + "<br>");
        //console.log("answer  2=" + answers[acounter + 1]);
        $('#answer3').html("<input type=\"radio\" name=\"answer\" value=\"" + answers[acounter + 2] + "\">" + answers[acounter + 2] + "<br>");
        //console.log("answer  3=" + answers[acounter + 2]);
        $('#answer4').html("<input type=\"radio\" name=\"answer\" value=\"" + answers[acounter + 3] + "\">" + answers[acounter + 3] + "<br>");
        //console.log("answer  4=" + answers[acounter + 3]);
        qcounter++;
        acounter += 4;
        qNum++;
        //function to run when user selects an answer
        $('#content input').on('change', function() {
            answer = $('input[name=answer]:checked', '#content').val();
            $('input[name=answer]').attr("disabled",true);
            question = questionId[cur];
            cur++;
            //console.log(curQuestion);
            //console.log(userAnswer);
            $('#next').html('Next');
            //ajax call to send over the question id, user answer, and username 
            //to use php/mysql to check answer
            //console.log(answer);
            $.ajax({
                type: "POST",
                url: 'verifyAnswer.php',
                data: {
                  question: question,
                  answer: answer,
                  match: match
                }
            //done function to show result if user got it right or wrong
            }).done(function(message){
                var result = JSON.parse(message);
                //console.log(curQuestion);
                if (result.status == 'right') {
                  $('#next').show();
                  $('#picture').attr("src", "right.gif");
                  //console.log('right');
                  //console.log(result.message);
                  correct++;
                }else if(result.status == 'wrong'){
                  $('#next').show();
                  $('#picture').attr("src", "wrong.gif");
                  //console.log('wrong');
                  //console.log(result.message);
                }
            });
        });
    });
});
</script>
</body>
</html>