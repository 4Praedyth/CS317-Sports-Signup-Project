<?php
require_once 'init.php'; // database connection, etc.
// no security check for this page

// is form being submitted
if ( isset($_POST["form1"]) ) {
    $user_type = trim($_POST["user_type"]);
    $email = trim($_POST["email"]);
    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]); 
    $age = trim($_POST["age"]);
    $password = trim($_POST["password1"]);

    if(!($email=='' ||  $name=='' || $phone=='' || $age==''|| $password=='')) {
      // Check if the email exists in the Player table
      $sqlPlayer = "SELECT * FROM Player WHERE player_email = '" . $email . "'";
      $resultPlayer = db_query($sqlPlayer);

      // Check if the email exists in the Coach table
      $sqlCoach = "SELECT * FROM Coach WHERE coach_email = '" . $email . "'";
      $resultCoach = db_query($sqlCoach);
      if ($resultPlayer->num_rows > 0 || $resultCoach->num_rows > 0) {
          $message  = "Email Already Exists!"; // fall back through to form with message
      }
      else {
         if($user_type == 'player'){
          $sql = "INSERT INTO Player(player_email,player_name, player_phone, player_age, player_password) values
                  ('" . $email . "','" .$name . "','" . $phone . "','" . $age . "', '" . $password . "')";
          //var_dump($sql);exit;
          $result = db_query($sql);
         }
        else{
          $sql = "INSERT INTO Coach(coach_email,coach_name,coach_phone,coach_age, coach_password) values
          ('" . $email . "','" .$name . "','" . $phone . "','" . $age . "', '" . $password . "')";
          //var_dump($sql);exit;
          $result = db_query($sql);
        }
        
        // transfer to login page
        header("Location: index.php?message=account_created");
        exit;
      }
    }
}

?>
<!DOCTYPE html>
<html>
<br></br>
<head><title>Sign up for LFSP</title></head>
<br></br>
<style>
  h1 {text-align: center;}
  h3 {text-align: center;}
  form {text-align: center;}
  body{background-image: url('https://site.surveysparrow.com/wp-content/uploads/2023/04/Sports-Registration-form-template.png');}
  .box {
            background-color: rgba(255, 255, 255, 0.7);
            text-align: center;
            position: absolute;
            width: 300px;
            padding: 20px;
            border-radius: 8px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
</style>
<script>
  function checkPasswords() {

      var email = document.getElementById('email').value.trim();
      var name = document.getElementById('name').value.trim();
      var phone = document.getElementById('phone').value.trim();
      var age = document.getElementById('age').value.trim();
      var pass1 = document.getElementById('password1').value.trim();
      var pass2 = document.getElementById('password2').value.trim();

      if (email == '' || name == '' || phone == '' || age == '' || pass1 == '') {
        window.alert('Please fill out all fields!');
        return false;
      }

      if (pass1 != pass2) {
          window.alert('The passwords do not match!');
          return false;  // abort form submit
      }

      return true; // submit form
  }
  
      function goToHomePage() {
          // Redirect to the home page
          window.location.href = 'index.php';
      }

</script>
<body>
<div class="box" item="center">
<? if (isset($message)) { ?>
    <h1><?= $message ?></h1>
    Please Try Again
<? } ?>

<h1>Sign up for Lake Forest Sports Program</h1>
<h3>Please enter your information below</h3>
<form action="signup.php" method="POST" id="form1" onsubmit="return checkPasswords()">
  <input type="hidden" name="form1" value="1">
    Select One: <select name="user_type">
                  <option value="player">Player</option>
                  <option value="coach">Coach</option>
                </select>
    <br><br>
    Email: <input type="text" name="email" id = "email">
    <br><br>
    Name: <input type="text" name="name" id = "name">
    <br><br>
    Phone: <input type="text" name="phone" id = "phone">
    <br><br>
    Age: <input type="text" name="age" id = "age">
    <br><br>
    Password: <input type="password" name="password1" id="password1">
    <br><br>
    Re-type Password: <input type="password" name="password2" id="password2">
    <br><br>
    <div class="button-container">
    <input type="submit" value="Sign Up">
    &nbsp;&nbsp;
    <input type="reset" value="Clear">
    <br></br>
    <br></br>
    <input type="button" value="Home" onclick="goToHomePage()">


</form>
</div>
</body>
</html>