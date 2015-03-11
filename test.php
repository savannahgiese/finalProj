  <?php
$q = intval($_GET['q']);
echo $q;
session_start();
include 'database.php';
$index = 4;
$results = array('status' => NULL, 'index' => NULL, 'question' => NULL);
$index = $results['index'];

//select question
$sql="SELECT question FROM questions WHERE id = '".$q."'";
$result=mysqli_query($mysqli,$sql);
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
printf ("%s\n",$row["question"]);

mysqli_free_result($result);
mysqli_close($mysqli);
?>