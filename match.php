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
</head>
<body>
<div id="wrap">
    <div id="header">
		<h1>
			Sherlock Trivia
		</h1>
	</div>
    <?php 
    if(session_status() == PHP_SESSION_ACTIVE){
        if(isset($_SESSION['username']) && $_SESSION['username'] != NULL){
            //$username = $_SESSION['username'];
            echo "<div id=\"navigation\"><ul>
    		<li><a href=\"http://savvyg.me/Final_Project/welcome.php\">Home</a></li>
    		<li><a href=\"http://savvyg.me/Final_Project/user.php\">User</a></li>
    		<li><a href=\"http://savvyg.me/Final_Project/play.php\">Play</a></li>
    		<li><a href=\"http://savvyg.me/Final_Project/logout.php\">Logout</a></li></ul></div>";
        }
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $questions = [];
            $answers = [];
            $userAns = [];
            $user = $_GET['userId'];
            $match = $_GET['mid'];
            echo "<br><br>";
            if(!is_numeric($user) || !is_numeric($match)){
                echo "Invalid input.";
            } else {
                //get the questions that were in the match
                if (!($stmt = $mysqli->prepare("SELECT questions.question FROM `questions` 
                INNER JOIN `answers` ON answers.qid = questions.id INNER JOIN `completed_answers` 
                ON completed_answers.aid = answers.id INNER JOIN `match` 
                ON match.id = completed_answers.mid WHERE match.id = ?"))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!($stmt->bind_param("i", $match))){
                    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                
                $stmt->bind_result($quest);
                while ($stmt->fetch()) {
                    array_push($questions, $quest);
                }
                //print_r(array_values($questions));
                //get the user answers from the match
                if (!($stmt = $mysqli->prepare("SELECT answers.answer FROM  `answers` 
                INNER JOIN  `completed_answers` ON completed_answers.aid = answers.id
                INNER JOIN  `match` ON match.id = completed_answers.mid
                WHERE match.id = ?"))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!($stmt->bind_param("i", $match))){
                    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                
                $stmt->bind_result($uAns);
                while ($stmt->fetch()) {
                    array_push($userAns, $uAns);
                }
                
                if (!($stmt = $mysqli->prepare("SELECT answers.answer FROM `answers` 
                INNER JOIN `completed_answers` 
                ON completed_answers.aid = answers.id INNER JOIN `match` 
                ON match.id = completed_answers.mid WHERE match.id = ? AND answers.correct = 1"))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!($stmt->bind_param("i", $match))){
                    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                
                $stmt->bind_result($ans);
                while ($stmt->fetch()) {
                    array_push($answers, $ans);
                }
                
                if (!($stmt = $mysqli->prepare("SELECT COUNT(*) FROM `questions` 
                INNER JOIN `answers` ON answers.qid = questions.id INNER JOIN `completed_answers` 
                ON completed_answers.aid = answers.id INNER JOIN `match` 
                ON match.id = completed_answers.mid WHERE match.id = ?"))){
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!($stmt->bind_param("i", $match))){
                    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                $stmt->bind_result($id);
                $count = 0;
                while ($stmt->fetch()) {
                    $count = $id;
                }
                if($count == 0){
                    echo "Data does not exist.";   
                }else{
                    echo "Share with others: http://savvyg.me/Final_Project/match.php?userId=" . $user . "&mid=" . $match;
                    echo "<table>";
                    echo "<thead align=\"right\">";
                    echo "<tr>";
                    echo "<td>Questions</td>";
                    echo "<td>Selected Answers</td>";
                    echo "<td>Correct Answers</td>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    for($int = 0; $int < $count; $int++){
                        echo "<tr>";
                        echo "<td>";
                        echo $questions[$int] . "<br>";
                        echo "</td>";
                        echo "<td>";
                        echo $userAns[$int] . "<br>";
                        echo "</td>";
                        echo "<td>";
                        echo $answers[$int] . "<br>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                }
            }
        }
    }?>
</body>
</html>