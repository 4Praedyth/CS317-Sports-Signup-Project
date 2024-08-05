<?php
require_once 'init.php';

$page_access_level = 'admin'; // set to 'user' or 'admin' before including security 
require_once 'securityCheck.php'; // Must be logged in to get past here


if ( isset($_POST["playerForm"]) ) {
    $email = trim($_POST["email"]);
    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]); 
    $age = trim($_POST["age"]);
    $password = trim($_POST["psw"]);
    $sport = trim($_POST["sport"]);
    $sex= trim($_POST["sex"]);
    
    if(!($email=='' ||  $name=='' || $phone=='' || $age==''|| $password=='')) {
      // Check if the email exists in the Player table
      $sqlPlayer = "SELECT * FROM Player WHERE player_email = '" . $email . "'";
      $resultPlayer = db_query($sqlPlayer);
      if ($resultPlayer->num_rows > 0) {
          $message  = "Email Already Exists!"; // fall back through to form with message
	  }
      else {
          $sql = "INSERT INTO Player(player_email,player_name, player_phone, player_age, player_password, player_sport, player_sex) values
                  ('" . $email . "','" .$name . "','" . $phone . "','" . $age . "', '" . $password . "','" . $sport ."','" . $sex . "')";
          //var_dump($sql);exit;
          $resultPlayer = db_query($sql);
        // transfer to login page
        header("Location: admin_welcome.php");
        exit;
    }
    }
    }
    

if ( isset($_POST["coachForm"]) ){
    $email = trim($_POST["email"]);
    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $age = trim($_POST["age"]);
    $password = trim($_POST["psw"]);
    $sport = trim($_POST["sport"]);
    $sex= trim($_POST["sex"]);
    
if(!($email=='' ||  $name=='' || $phone=='' || $age==''|| $password=='')) {
      // Check if the email exists in the Coach table
      $sqlCoach = "SELECT * FROM Coach WHERE coach_email = '" . $email . "'";
      $resultCoach = db_query($sqlCoach);
      if ($resultCoach->num_rows > 0) {
          $message  = "Email Already Exists!"; // fall back through to form with message
	  }
      else {
          $sqlCoach = "INSERT INTO Coach(coach_email,coach_name, coach_phone, coach_password, coach_sport, coach_age, coach_sex) values
                  ('" . $email . "','" .$name . "','" . $phone . "','" . $password . "','" . $sport ."','" . $age."','" . $sex."')";
          //var_dump($sql);exit;
          $resultCoach = db_query($sqlCoach);
        // transfer to login page
        header("Location: admin_welcome.php");
        exit; 
    }
    }
    }
    
if ( isset($_POST["teamForm"]) ){
    $name = trim($_POST["name"]);
    $age = trim($_POST["age"]);
    $sport = trim($_POST["sport"]);
    $sex= trim($_POST["sex"]);
    $head_coach= trim($_POST["head_coach"]);
    $asst_coach= trim($_POST["asst_coach"]);
    
if(!( $name=='' || $age=='' || $sport == '')) {
      // Check if the email exists in the Coach table
      $sqlTeam = "SELECT * FROM Team WHERE team_name = '" . $name . "'";
      $resultTeam = db_query($sqlTeam);
      if ($resultTeam->num_rows > 0) {
          $message  = "Team Name Already Exists!"; // fall back through to form with message
	  }
      else {
          $sqlTeam= "INSERT INTO Team(team_name,team_age,team_sex, team_sport) values
                  ('" . $name . "','" .$age . "','" . $sex ."','" . $sport ."')";
          //var_dump($sql);exit;
          $resultTeam = db_query($sqlTeam);
          
          
        // transfer to login page
        header("Location: admin_welcome.php");
        exit; 
    }
    }
    }


if ( isset($_POST["sportForm"]) ){
    $name = trim($_POST["name"]);
    $size= trim($_POST["size"]);
    $asstCoachMax = trim($_POST["asstCoachMax"]);
    $playerFee= trim($_POST["player fee"]);
    $head_pay= trim($_POST["head pay"]);
    $assit_pay= trim($_POST["assit pay"]);
    $unif = trim($_POST["unifID"]);
    
if(!( $name=='')) {
      // Check if the email exists in the Coach table
      $sqlSport = "SELECT * FROM Sport WHERE sport_type = '" . $name . "'";
      $resultSport = db_query($sqlSport);
      if ($resultSport->num_rows > 0) {
          $message  = "Sport Already Exists!"; // fall back through to form with message
	  }
      else {
      	  $unif = "INSERT INTO UnifProfile(unifProfile_id) values ('".$unif."')";
      	  $resultUni = db_query($unif);
      
          $sqlSport= "INSERT INTO Sport(sport_type,sport_roaster_size,sport_asst_coach_max, sport_player_fee,sport_head_coach_pay,sport_asst_coach_pay) values
                  ('" . $name . "','" .$size . "','" .$asstCoachMax ."','" .$playerFee."','" .$head_pay."','" .$assit_pay."')"; 
          //var_dump($sql);exit;
          $resultSport = db_query($sqlSport);
          
        // transfer to login page
        header("Location: admin_welcome.php");
        exit; 
    }
    }
    }



?>

<!DOCTYPE html>
<html>
<head>
  <title>Welcome to the Lake Forest Sports Program</title>
  <style>
  
  body{background-image:  url('https://i.pinimg.com/564x/29/cd/5d/29cd5d9310ae0f771ef24abe5c467927.jpg');}
  
  .center {
  margin-left: auto;
  margin-right: auto;
}

  header h1 {
  text-align: center;
  font-size: 40px;
  font-weight: 600;
  background-image: linear-gradient(45deg, #553c9a, #ee4b2b);
  color: transparent;
  background-clip: text;
  -webkit-background-clip: text;
}

header h3 {
  text-align: center;
  font-size: 30px;
  font-weight: 600;
  background-image: linear-gradient(45deg, #553c9a, #ee4b2b);
  color: transparent;
  background-clip: text;
  -webkit-background-clip: text;
}
    table, th, td {
      border:2px solid black;
      text-align: center;
      height: 30px;
      width: auto;
      padding:10px;
      font-size: 20px;
     }
    {box-sizing: border-box;}
  th{
  height: 30px;
  background-color:lavender;
  padding:10px;
  }
  th:hover {background-color:lavenderblush;}
  td{
    height: 30px;
    padding:10px;
    font-weight: normal;
	}
	
	td:hover {background-color: lavenderblush;}
	
/* Button used to open the contact form - fixed at the bottom of the page */
.open-button-player {background-color: #c166d1;
  color: white;
  padding: 16px 20px;
  border: black;
  cursor: pointer;
  opacity: 0.8;
  position: relative;
  left: 120px;
  top:2px;
  bottom: 23px;
  right: 28px;
  width: 280px;}
  
.open-button-player{
  transition-duration: 0.4s;
  background-color: white;
  color: #6c02a6;
  border: 4px solid #6c02a6;}

.open-button-player:hover {
  background-color: #6c02a6; /* Green */
  color: white;
}

/* The popup form - hidden by default   margin:auto; */
.form-popup-player {
  display: none;
  padding: 30px;
  top: 10px;
  bottom: 10px;
  border: 15px solid #f1f1f1;
  z-index: 1;
  right: 300px;
  margin-left: 300px;
  margin-right: 300px;
}

/* Add styles to the form container */
.form-container-player {
  max-width: 350px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container-player input[type=text], .form-container-player input[type=password] {
  width: 180%;
  padding: 15px;
  margin: 5px 0 5px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container-player input[type=text]:focus, .form-container-player input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container-player .btn {
  background-color: #c166d1;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container-player .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container-player .btn:hover, .open-button-player:hover {
  opacity: 1;
}
    
    
    
.open-button-coach {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: relative;
  left:120px;
  top:2px;
  bottom: 23px;
  right: 28px;
  width: 280px;
}


.open-button-coach{
  transition-duration: 0.4s;
  background-color: white;
  color: #6c02a6;
  border: 4px solid #6c02a6;}

.open-button-coach:hover {
  background-color: #6c02a6; /* Green */
  color: white;
}

/* The popup form - hidden by default */
.form-popup-coach {
  display: none;
  padding: 20px;
  top: 5px;
  position: relative;
  bottom: 100;
  border: 15px solid #f1f1f1;
  z-index: 9;
  margin-left: 300px;
  margin-right: 300px;
}

/* Add styles to the form container */
.form-container-coach {
  max-width: 350px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container-coach input[type=text], .form-container-coach input[type=password] {
  width: 180%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container-coach input[type=text]:focus, .form-container-coach input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container-coach .btn {
  background-color: #c166d1;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container-coach .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container-coach .btn:hover, .open-button-coach:hover {
  opacity: 1;
  
}


.open-button-team {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: relative;
  left:120px;
  top:2px;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

.open-button-team{
  transition-duration: 0.4s;
  background-color: white;
  color: #6c02a6;
  border: 4px solid #6c02a6;}

.open-button-team:hover {
  background-color: #6c02a6; /* Green */
  color: white;
}

/* The popup form - hidden by default */
.form-popup-team {
  display: none;
  padding: 20px;
  top: 5px;
  position: relative;
  bottom: 100;
  border: 15px solid #f1f1f1;
  z-index: 9;
  margin:auto;
  margin-left: 300px;
  margin-right: 300px;
}

/* Add styles to the form container */
.form-container-team {
  max-width: 350px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container-team input[type=text], .form-container-team input[type=password] {
  width: 180%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container-team input[type=text]:focus, .form-container-team input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container-team .btn {
  background-color: #c166d1;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container-team .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container-team .btn:hover, .open-button-team:hover {
  opacity: 1;
  
}


/* Button used to open the contact form - fixed at the bottom of the page */
.open-button-sport {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: relative;
  left:120px;
  top:2px;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

.open-button-sport{
  transition-duration: 0.4s;
  background-color: white;
  color: #6c02a6;
  border: 4px solid #6c02a6;}

.open-button-sport:hover {
  background-color: #6c02a6; /* Green */
  color: white;
}

/* The popup form - hidden by default */
.form-popup-sport {
  display: none;
  padding: 20px;
  top: 5px;
  position: relative;
  right: 400px
  bottom: 100;
  border: 15px solid #f1f1f1;
  z-index: 9;
  margin:auto;
  margin-left: 300px;
  margin-right: 300px;
}

/* Add styles to the form container */
.form-container-sport {
  max-width: 350px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container-sport input[type=text], .form-container-sport input[type=password] {
  width: 180%;
  padding: 15px;
  margin: 5px 0 5px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container-sport input[type=text]:focus, .form-container-sport input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container-sport .btn {
  background-color: #c166d1;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container-sport .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container-sport .btn:hover, .open-button-sport:hover {
  opacity: 1;
}
    
  </style>
</head>

<script>
function playeropenForm() {
  document.getElementById("playerForm").style.display = "block";
}

function playercloseForm() {
  document.getElementById("playerForm").style.display = "none";
}


function coachopenForm() {
  document.getElementById("coachForm").style.display = "block";
}

function coachcloseForm() {
  document.getElementById("coachForm").style.display = "none";
}

function teamopenForm() {
  document.getElementById("teamForm").style.display = "block";
}

function teamcloseForm() {
  document.getElementById("teamForm").style.display = "none";
}

function sportopenForm() {
  document.getElementById("sportForm").style.display = "block";
}

function sportcloseForm() {
  document.getElementById("sportForm").style.display = "none";
}

</script>


<body>
  <hr>
  <?=$_SESSION["email"]?> is Signed In.  <a href="securityCheck.php?log_out=yes">Sign Out</a>
  <hr>
  <header>
  <h1>Lake Forest Sports Program Admin Page</h1>
  </header>
  
<button class="open-button-player" onclick="playeropenForm()">Create a New Player</button>
<!-- The form -->
<div class="form-popup-player" id="playerForm">
  <form action="admin_welcome.php" method="POST" id="playerForm" class="form-container-player">
  <input type="hidden" name="playerForm" value="1">
    <h1>Create A New Player</h1>
    
    <label for="name"><b>Name</b></label>
    <input type="text" placeholder="Enter Player Name" name="name" required>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Player Email" name="email" required>
    
    <label for="phone"><b>Phone</b></label>
    <input type="text" placeholder="Enter Player Phone Number" name="phone" required>
    
    <label for="age"><b>Age</b></label>
    <input type="text" placeholder="Enter Player Age" name="age" required>
    
    <label for="sex"><b>Sex</b></label>
    <select name="sex">
       <option value ="Male">Male</option>
       <option value ="Female">Female</option>
       </select>
    
    <br></br>
    <label for="sport"><b>Sport</b></label>
     <select name="sport"> 
                //populate value using php
                <?php
                    $query = "SELECT * FROM Sport";
                    $results=db_query($query);
                    //loop
                    foreach ($results as $sport)
                    {
				echo'<option value="'.$sport["sport_type"].'">'.           
				$sport["sport_type"].'</option>';
				}
                    
                ?>
                <option value="<?php echo $sport ?>"</option>
        
    </select>

	<br></br>
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Player Password" name="psw" required>
    <br></br>

    <button type="submit" class="btn">Create</button>
    <button type="button" class="btn cancel" onclick="playercloseForm()">Close</button>
  </form>
</div> 
    

<button class="open-button-coach" onclick="coachopenForm()">Create a New Coach</button>
<!-- The form -->
<div class="form-popup-coach" id="coachForm">
  <form action="admin_welcome.php" method="POST" id="coachForm" class="form-container-coach">
  <input type="hidden" name="coachForm" value="1">
    <h1>Create A New Coach</h1>
    
    <label for="name"><b>Name</b></label>
    <input type="text" placeholder="Enter Coach Name" name="name" required>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Coach Email" name="email" required>
    
    <label for="phone"><b>Phone</b></label>
    <input type="text" placeholder="Enter Coach Phone Number" name="phone" required>
    
    <label for="age"><b>Age</b></label>
    <input type="text" placeholder="Enter Coach Age" name="age" required>
    
    <label for="sex"><b>Sex</b></label>
    <select name="sex">
       <option value ="Male">Male</option>
       <option value ="Female">Female</option>
       </select>
    <br></br>
    <label for="sport"><b>Sport</b></label>
    <select name="sport"> 
                //populate value using php
                <?php
                    $query = "SELECT * FROM Sport";
                    $results=db_query($query);
                    //loop
                    foreach ($results as $sport)
                    {
				echo'<option value="'.$sport["sport_type"].'">'.           
				$sport["sport_type"].'</option>';
				}
                    
                ?>
                <option value="<?php echo $sport ?>"</option>
        
    </select>	
    
    <br></br>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Coach Password" name="psw" required>
    <br></br>

    <button type="submit" class="btn">Create</button>
    <button type="button" class="btn cancel" onclick="coachcloseForm()">Close</button>
  </form>
</div> 

<button class="open-button-team" onclick="teamopenForm()">Create a New Team</button>
<!-- The form -->
<div class="form-popup-team" id="teamForm">
  <form action="admin_welcome.php" method="POST" id="teamForm" class="form-container-team">
  <input type="hidden" name="teamForm" value="1">
    <h1>Create A New Team</h1>

    <label for="name"><b>Name</b></label>
    <input type="text" placeholder="Enter Team Name" name="name" required>
    
    <label for="age"><b>Age</b></label>
    <input type="text" placeholder="Enter Team Age Restriction" name="age" required>


    <label for="sport"><b>Sport</b></label>
    <select name="sport"> 
                //populate value using php
                <?php
                    $query = "SELECT * FROM Sport";
                    $results=db_query($query);
                    //loop
                    foreach ($results as $sport)
                    {
				echo'<option value="'.$sport["sport_type"].'">'.           
				$sport["sport_type"].'</option>';
				}
                    
                ?>
                <option value="<?php echo $sport ?>"</option>
        
    </select>	
    
    <br></br>
    
    <label for="sex"><b>Sex</b></label>
    <select name="sex">
       <option value ="Male">Male</option>
       <option value ="Female">Female</option>
       </select>
    
    <br></br>
    <label for="head_coach"><b>Choose Head Coach</b></label>
    <select name="head_coach"> 
                //populate value using php
                <?php
                    $query = "Select concat(coach_name,' (',coach_sport, ')') as coaching FROM Coach";
                    $results=db_query($query);
                    //loop
                    foreach ($results as $coach)
                    {
				echo'<option value="'.$coach["coaching"].'">'.           
				$coach["coaching"].'</option>';
				}
                    
                ?>
                <option value="<?php echo $sport ?>"</option>
        
    </select>	
    <br></br>
     <label for="asst_coach"><b>Choose Assistant Coach</b></label>
    <select name="asst_coach"> 
                //populate value using php
                <?php
                    $query = "Select concat(coach_name,' (',coach_sport, ')') as coaching FROM Coach";
                    $results=db_query($query);
                    //loop
                    foreach ($results as $coach)
                    {
				echo'<option value="'.$coach["coaching"].'">'.           
				$coach["coaching"].'</option>';
				}
                    
                ?>
                <option value="<?php echo $sport ?>"</option>
                
    </select>
    
    <button type="submit" class="btn">Create</button>
    <button type="button" class="btn cancel" onclick="teamcloseForm()">Close</button>
  </form>
</div> 




<button class="open-button-sport" onclick="sportopenForm()">Create a New Sport</button>
<!-- The form -->
<div class="form-popup-sport" id="sportForm">
  <form action="admin_welcome.php" method="POST" id="sportForm" class="form-container-sport">
  <input type="hidden" name="sportForm" value="1">
    <h1>Create A New Sport</h1>

    <label for="name"><b>Sport</b></label>
    <input type="text" placeholder="Enter Sport Name" name="name" required>
    
    <label for="size"><b>Size</b></label>
    <input type="text" placeholder="Enter Sport Team Size" name="size" required>
    
     <label for="asstCoachMax"><b>Maximal Assitant Coach Number</b></label>
    <input type="text" placeholder="Enter Maximal Assitant Coach Number" name="asstCoachMax" required>
    
    <label for="player fee"><b>Player Fee</b></label>
    <input type="text" placeholder="Enter Player Fee" name="player fee" required>
    
    <label for="head Pay"><b>Head Coach Pay</b></label>
    <input type="text" placeholder="Enter Head Coach Payment" name="head pay" required>
    
    <label for="assit Pay"><b>Assistant Coach Pay</b></label>
    <input type="text" placeholder="Enter Assitant Coach Payment" name="assit pay" required>
    
    <label for="unifID"><b>Uniform ID</b></label>
    <input type="text" placeholder="Enter Sport Uniform ID" name="unifID" required>
    
    
    <button type="submit" class="btn">Create</button>
    <button type="button" class="btn cancel" onclick="sportcloseForm()">Close</button>
  </form>
</div>     
  
  <?php
  $sql = "SELECT * FROM Team
    Left Join Head on team_name = Head.head_team_name
    LEFT Join Asst on team_name = Asst.asst_team_name
    Left join Sport on team_sport = Sport.sport_type";
  $result = db_query($sql);
  $sqlPlayer = "SELECT * FROM Player";
  $player = db_query($sqlPlayer);
  
  $sqlCoach = "SELECT * FROM Coach";
  $coach = db_query($sqlCoach);
  
  $SportSql = "SELECT * FROM Sport";
  $Sport = db_query($SportSql);
  ?>

<header>
<h3>Current Available Sport<h3>
</header>
  <table class="center">
    <tr>
      <th>Sport</th>
      <th>Roaster Limit</th>
      <th>Player Fee</th>
      <th>Maximal Assistant Coach Number</th>
      <th>Head Coach Payment</th>
      <th>Assistant Coach Payment</th>
      <th>Uniform ID</th>
    </tr>
  
  <? while ($sportResult = $Sport->fetch_assoc()) { ?>
      <tr>
      <td><? echo $sportResult["sport_type"];?></td>
      <td><? echo $sportResult["sport_roaster_size"];?></td>
      <td><? echo $sportResult["sport_player_fee"];?></td>
      <td><? echo $sportResult["sport_asst_coach_max"];?></td>
      <td><? echo $sportResult["sport_head_coach_pay"];?></td>
      <td><? echo $sportResult["sport_asst_coach_pay"];?></td>
      <td><? echo $sportResult["sport_unif_prof_id"];?></td>
      </tr>
  <? } ?>
  </table>
  
  
<header>
<h3>Current Team Information <h3>
</header>
  <table class="center">
    <tr>
      <th>Team</th>
      <th>Sport Type</th>
      <th>Sex</th>
      <th>Age</th>
      <th>Player Fee</th>
      <th>Head Coach</th>
      <th>Head Coach Pay</th>
      <th>Assistant Coach</th>
      <th>Assistant Coach Pay</th>
    </tr>
  
  <? while ($adminResult = $result->fetch_assoc()) { ?>
      <tr>
      <td><? echo $adminResult["team_name"];?></td>
      <td><? echo $adminResult["team_sport"];?></td>
      <td><? echo $adminResult["team_sex"];?></td>
      <td><? echo $adminResult["team_age"];?></td>
      <td><? echo $adminResult["sport_player_fee"];?></td>
      <td><? echo $adminResult["head_coach_email"];?></td>
      <td><? echo $adminResult["head_pay"];?></td>
      <td><? echo $adminResult["asst_coach_email"];?></td>
      <td><? echo $adminResult["asst_pay"];?></td>
      </tr>
  <? } ?>
  </table>
  
<header>
<h3>Current Player Information <h3>
</header>
  <table class="center">
    <tr>
      <th>Name</th>
      <th>Sex</th>
      <th>Age</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Sport</th>
      <th>Team</th>
      <th>Password</th>
    </tr>
  
  <? while ($playerResult = $player->fetch_assoc()) { ?>
      <tr>
      <td><? echo $playerResult["player_name"];?></td>
      <td><? echo $playerResult["player_sex"];?></td>
      <td><? echo $playerResult["player_age"];?></td>
      <td><? echo $playerResult["player_email"];?></td>
      <td><? echo $playerResult["player_phone"];?></td>
      <td><? echo $playerResult["player_sport"];?></td>
      <td><? echo $playerResult["player_team"];?></td>
      <td><? echo $playerResult["player_password"];?></td>
      </tr>
  <? } ?>
  </table>
  
<header>
<h3>Current Coach Information <h3>
</header>
  <table class="center">
    <tr>
      <th>Name</th>
      <th>sex</th>
      <th>Age</th>
      <th>Sport</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Password</th>
    </tr>
  
  <? while ($coachResult = $coach->fetch_assoc()) { ?>
      <tr>
      <td><? echo $coachResult["coach_name"];?></td>
      <td><? echo $coachResult["coach_sex"];?></td>
      <td><? echo $coachResult["coach_age"];?></td>
      <td><? echo $coachResult["coach_sport"];?></td>
      <td><? echo $coachResult["coach_email"];?></td>
      <td><? echo $coachResult["coach_phone"];?></td>
      <td><? echo $coachResult["coach_password"];?></td>
      </tr>
  <? } ?>
  </table>


</body>
</html>