<?php
require_once 'init.php'; // database connection, etc.
// no security check for this page

// is form being submitted
if ( isset($_POST["email"]) ) {
    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $userid = trim($_POST["email"]);
    $password = trim($_POST["password1"]);
    $age = trim($_POST["age"]);
    $fullname = $fname . ' ' . $lname;
    $admin = 2

    if ($fname=='' || $lname=='' || $email=='' || $password=='' ) {
      $message  = "Insufficient Data Supplied"; // fall back through to form with message
    }
    else {
      //check to see if user already exists
      $sql = "SELECT * FROM Player WHERE player_email = '$userid' ";
      //var_dump($sql);exit;
      $result = db_query($sql);
      if ($result->num_rows > 0) {
          $message  = "Username Already Exists!"; // fall back through to form with message
      }
      else {
          $sql = "INSERT INTO Player(player_email,player_name,player_password, player_age) values ('$userid','$fullname','$password','$age')";
          //var_dump($sql);exit;
          $result = db_query($sql);

          // transfer to login page
          header("Location: welcomePlayer.php");
          exit;
      }
    }
}

?>
<!DOCTYPE html>
<html>
<head><title>Sign up for the View Ridge Gallery</title></head>
<script>
  function checkPasswords() {
      var pass1 = document.getElementById('password1').value.trim();
      var pass2 = document.getElementById('password2').value.trim();
      if (pass1 != pass2) {
        window.alert('The passwords do not match!');
        return false;  // abort form submit
      }
      return true; // submit form
  }
</script>
<body>

<? if (isset($message)) { ?>
    <h1><?= $message ?></h1>
    Please Try Again
<? } ?>

<h1>Sign up for Lake Forest Sports Program</h1>
<h3>Please enter your information below</h3>


<form action="signup.php" method="POST" onsubmit="return checkPasswords()">
    <br><br>
    First name: <input type="text" name="fname">
    <br><br>
    Last name: <input type="text" name="lname">
    <br><br>
    Age: <input type="text" name="age">
    <br><br>
    Email Address: <input type="text" name="email">
    <br><br>
    Password: <input type="password" name="password1" id="password1">
    <br><br>
    Re-type Password: <input type="password" name="password2" id="password2">
    <br><br>

    <br><br>
    <input type="submit" value="Sign Up">
    &nbsp;&nbsp;
    <input type="reset" value="Clear">
</form>


</body>
</html>