<?php
session_start();
include 'database.php';
$username = $_SESSION['username'];
$question = mysqli_real_escape_string($mysqli, trim($_POST["question"]));
$answer = mysqli_real_escape_string($mysqli, trim($_POST["answer"]));
$match = mysqli_real_escape_string($mysqli, trim($_POST["match"]));
$results = array('status' => NULL, 'message' => NULL);

if (!($stmt = $mysqli->prepare("SELECT * FROM answers WHERE answer = ? AND qid = ? AND correct = 1"))) {
    //$results['message'].= "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("si", $answer, $question))){
    //$results['message'].= "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    //$results['message'].= "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
mysqli_stmt_store_result($stmt);
$numRows = mysqli_stmt_num_rows($stmt);

if (!($stmt = $mysqli->prepare("SELECT `id` FROM `answers` WHERE answer = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!($stmt->bind_param("s", $answer))){
    echo "Binding parameters failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_result($id);
$ansId = 0;
while ($stmt->fetch()) {
    $ansId = $id;
}

if (!$stmt = $mysqli->prepare("INSERT INTO `completed_answers` (`aid`, `mid`) VALUES (?, ?)")){
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("ii", $ansId, $match)) {
  echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
//var_dump($stmt);
if (!$stmt->execute()) {
  echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if ($numRows == 0) {
    //check if the answer is wrong
    $results['status'] = 'wrong';
    //$results['message'].= $match;
    //$results['message'].= $question;
}else{
    //else the answer is right
    $results['status'] = 'right';
    //$results['message'].= $match;
    //$results['message'].= $question;
}
$mysqli->close();
echo json_encode($results);
?>