<?php
session_start();
include 'database.php';
$username = $_SESSION['username'];
$question = mysqli_real_escape_string($mysqli, trim($_POST["question"]));
$answer = mysqli_real_escape_string($mysqli, trim($_POST["answer"]));
$match = mysqli_real_escape_string($mysqli, trim($_POST["match"]));
$results = array('correct_ans' => NULL, 'message' => NULL);
$results['message'] = $answer;

//get's correct answer from database
if (!($stmt = $mysqli->prepare("SELECT `answer` FROM answers WHERE qid = ? AND correct = 1"))) {
    //$results['message'].= "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("i", $question))){
    //$results['message'].= "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    //$results['message'].= "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($ans);
$correct = 0;
while ($stmt->fetch()) {
    $correct = $ans;
}
$results['correct_ans'] = $correct;

if (!($stmt = $mysqli->prepare("SELECT `id` FROM `answers` WHERE qid = ? AND answer = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("is", $question, $answer))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($id);
$guess = 0;
while ($stmt->fetch()) {
    $guess = $id;
}
$results['message'] .= $guess;

//to get the users id
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

//to get the match id
if (!($stmt = $mysqli->prepare("SELECT `id` FROM `match` WHERE matchNum = ? AND uid = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("ii", $match, $userId))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_result($id);
$match = 0;
while ($stmt->fetch()) {
    $match = $id;
}

//inserting the users guess id and match id into completed_answers
if (!$stmt = $mysqli->prepare("INSERT INTO `completed_answers` (`aid`, `mid`) VALUES (?, ?)")){
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("ii", $guess, $match)) {
  echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
//var_dump($stmt);
if (!$stmt->execute()) {
  echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$mysqli->close();
echo json_encode($results);
?>