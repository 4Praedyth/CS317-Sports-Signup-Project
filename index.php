<?php
require_once 'init.php'; // database connection, etc.
// no security check for this page

  if ( isset($_POST["email"]) ) {
  // login form submitted

  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $position= trim($_POST["position"]);



  if(isset($_POST['position']) && $_POST['position'] == 'Athlete'){
    $sqlPlayer = "SELECT * FROM Player
    WHERE player_email = '" . $email . "' AND player_password = '" . $password . "'";
    $resultPlayer = db_query($sqlPlayer);

    
    if ($resultPlayer->num_rows > 0) {
      $row = $resultPlayer->fetch_assoc();

      //Set session variables
      $_SESSION["email"] = $email;
      $_SESSION["password"] = $password;
      $_SESSION["fullname"] = $row["fname"] . " " . $row["lname"];
      $_SESSION["is_coach"] = False;
      $_SESSION["is_admin"] = False;
      $_SESSION["is_player"] = True;

      header("Location: player_welcome.php");  //NEEDS TO BE CHANGED TO PLAYER PAGE
    } else {
        $message = "Login Attempt Failed. Please Try Again";
    }
  }else if(isset($_POST['position']) && $_POST['position'] == 'Coach'){
      $sqlCoach = "SELECT * FROM Coach 
      WHERE coach_email = '" . $email . "' AND coach_password = '" . $password . "'";
      $resultCoach = db_query($sqlCoach);

      if ($resultCoach->num_rows > 0) {
        $row = $resultCoach->fetch_assoc();

        // Set session variables for coach
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;
        $_SESSION["fullname"] = $row["fname"] . " " . $row["lname"]; // Use correct column names
        $_SESSION["is_coach"] = True;
        $_SESSION["is_player"] = False;
        $_SESSION["is_admin"] = False;
    
        header("Location: coach_welcome.php"); //NEEDS TO BE CHANGED TO COACH PAGE
    
      } else {
        $message = "Login Attempt Failed. Please Try Again";
      }
  }else if(isset($_POST['position']) && $_POST['position'] == 'Admin'){
      $sqlAdmin = "SELECT * FROM Admin
      WHERE admin_email = '" . $email . "' AND admin_password = '" . $password . "'";
      $resultAdmin = db_query($sqlAdmin);
    
      if ($resultAdmin->num_rows > 0) {
        $row = $resultAdmin->fetch_assoc();

        // Set session variables for coach
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;
        $_SESSION["fullname"] = $row["fname"] . " " . $row["lname"]; // Use correct column names
        $_SESSION["is_coach"] = False;
        $_SESSION["is_admin"] = True;
        $_SESSION["is_player"] = False;
    
        header("Location: admin_welcome.php"); //NEEDS TO BE CHANGED TO COACH PAGE
    
      } else {
        $message = "Login Attempt Failed. Please Try Again";
      }
    }
  }





  

?>


<!DOCTYPE html>
<html>
<head><title>LFSP</title></head>
<style>
  h1 {text-align: center;}
  h3 {text-align: center;}
  form {text-align: center;}
  body{background-image:  url('https://www.lakeforest.edu/Public/OCM/1600x900_Homepage/Aerial_1_1600x900.png');}
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
<body>
<div class="box" item="center">
<br></br>
<? if (isset($message)) { ?>
    <h1><?= $message ?></h1>
<? } else{
  ?><h1>Welcome to the Lake Forest Sports Program</h1><?
} ?>
<br></br>
<h3>Please Sign In</h3>
<form action="index.php" method="POST">
    Email: <input type="text" name="email">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    Sign in as: <select name="position">
       <option value ="Athlete">Athlete</option>
       <option value ="Coach">Coach</option>
       <option value ="Admin">Admin</option>
       </select>
    <br><br>
    Don't have an account yet? <a href="signup.php">Sign up here</a>.
    <br><br>
    <input type="submit" value="Sign In">
    &nbsp;&nbsp;
    <input type="reset" value="Clear">
</form>
</div>
</body>
</html>