<?php
ini_set('display_errors', 'On');
session_start(); 
if(isset($_SESSION['username']) && $_SESSION['username'] !='') { 
  header( "Location: http://savvyg.me/Final_Project/welcome.php"); 
}?>

<!DOCTYPE html>
<html>

<head>
  <title>Sherlock Trivia</title>
  <link rel="stylesheet" href="style.css">
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
    <div id="content">
      <p>
        <img alt="Picture" id="picture" src="http://savvyg.me/Final_Project/Sherlock.jpg">
      </p>
      <p>
        <fieldset style="width:10%">
          <legend>LOG-IN HERE</legend>
          <form action="" method="POST" id="authentication" name="authentication">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" size=40/>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" size=40/>
            <input type="submit" id="login" value="Login" />
          </form>
          <div id="error"></div>
        </fieldset>
        <a href="create.php">Or Create User Here</a>
      </p>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('#authentication').submit(function(event) {
        event.preventDefault();
        var username = $('#username').val();
        var password = $("#password").val();
        //console.log(username);
        $.ajax({
          type: "POST",
          url: 'verifyLogin.php',
          data: {
            username: username,
            password: password
          }
        }).done(function(message){
          var result = JSON.parse(message);
          if (result.status == 'success') {
            //console.log('successful!');
            window.location.replace('welcome.php');
          } else {
            var obj = JSON.parse(message);
            $('#error').html(obj.error_message);
          }
        });
      });
    });
  </script>
</body>
<!-- 'success' => NULL, 'username' => NULL, 'blank_user' => NULL, 'blank_password' => NULL, 
'user_taken' => NULL, 'action' => $_POST['value'] -->
</html>