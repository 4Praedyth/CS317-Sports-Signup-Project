<?php
require_once 'init.php';

$page_access_level = 'coach'; // set to 'user' or 'admin' before including security check
require_once 'securityCheck.php'; // Must be logged in to get past here
$email = $_SESSION["email"];
$password = $_SESSION["password"];



if (isset($_COOKIE['selectedSport'])) {
  $selectedSport = $_COOKIE['selectedSport'];
}

if (isset($_COOKIE['selectedTeam'])) {
  $selectedTeam = $_COOKIE['selectedTeam'];
}

if (isset($_COOKIE['selectedRole'])) {
  $selectedRole = $_COOKIE['selectedRole'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  // Register form submission
  $selectedSport = $_POST['sport'];
  $selectedTeam = $_POST['team'];
  $selectedRole = $_POST['role'];  // Added line to get the selected role

  // Insert data or update if the email already exists
  $email = $_SESSION["email"];
  $password = $_SESSION["password"];

  $sqlHeadPayRole = "SELECT sport_head_coach_pay FROM Sport WHERE sport_type = '$selectedSport'";
  $resultHeadPayRole = db_query($sqlHeadPayRole);
  $headPayRole = $resultHeadPayRole->fetch_assoc()['sport_head_coach_pay'];

  $sqlAsstPayRole = "SELECT sport_asst_coach_pay FROM Sport WHERE sport_type = '$selectedSport'";
  $resultAsstPayRole = db_query($sqlAsstPayRole);
  $asstPayRole = $resultAsstPayRole->fetch_assoc()['sport_asst_coach_pay'];

  if ($selectedRole == "Head Coach") {
      // Perform query for Head Coach
      $sqlInsertHeadCoachInfo = "INSERT INTO Head (head_coach_email, head_team_name, head_pay)
                                 VALUES ('$email', '$selectedTeam', '$headPayRole')
                                 ON DUPLICATE KEY UPDATE head_team_name = '$selectedTeam'";

      db_query($sqlInsertHeadCoachInfo);
  } elseif ($selectedRole == "Assistant Coach") {
      // Perform query for Assistant Coach
      $sqlInsertAsstCoachInfo = "INSERT INTO Asst (asst_coach_email, asst_team_name, asst_pay)
                                 VALUES ('$email', '$selectedTeam', '$asstPayRole')
                                 ON DUPLICATE KEY UPDATE asst_team_name = '$selectedTeam'";

      db_query($sqlInsertAsstCoachInfo);
  }

  // Additional common query (if needed)
  $sqlInsertPlayerInfo = "INSERT INTO Player (player_email, player_password, player_sport, player_team)
                          VALUES ('$email', '$password', '$selectedSport', '$selectedTeam')
                          ON DUPLICATE KEY UPDATE 
                          player_sport = CONCAT(player_sport, ',', '$selectedSport'), 
                          player_team = '$selectedTeam'";

  db_query($sqlInsertPlayerInfo);

  // Redirect to a new page after successful form submission
header('Location: coach_welcome.php');
exit();

}


?>
<!DOCTYPE html>
<html>
<head>
  <title>Welcome to the Lake Forest Sports Program</title>
  <style>
  
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

header h2 {
  text-align: center;
  font-size: 30px;
  font-weight: 600;
  background-image: linear-gradient(45deg, #553c9a, #ee4b2b);
  color: transparent;
  background-clip: text;
  -webkit-background-clip: text;
}

header h3 {
  text-align: center;
  font-size: px;
  font-weight: 600;
  background-image: linear-gradient(45deg, #553c9a, #ee4b2b);
  color: transparent;
  background-clip: text;
  -webkit-background-clip: text;
}


        table{
            border: 2px solid black;
            text-align: center;
            height: 30px;
            width: 50%;
            padding: 10px;
            vertical-align: bottom;
        }

        td{
          width: 60%
        }

        th {
            height: 30px;
            width: 50%;
            background-color: lavender;
            padding: 10px;
        }
        
        th:hover {
            background-color: lavenderblush;
        }

        td {
            height: 30px;
            padding: 10px;
            font-weight: normal;
        }

        td:hover {
            background-color: lavenderblush;
        }

        .open-button-choose-uniforms {
            
            padding: 16px 20px;
            cursor: pointer;
            margin: 0 auto; 
            display: block;
            width: 100%; 

            }

        select {
            padding: 10px;
            box-sizing: border-box;
        }
        

        input[type="submit"] {
            width: 50%; 
            padding: 16px 20px; 
            box-sizing: border-box;
        }



    </style>
  <style>
    table, th, td {
      border:1px solid black;
    }
  </style>
</head>
<script>
        function setSportSelection() {
            var selectedSport = document.getElementById('sportSelect').value;
            document.cookie = "selectedSport=" + selectedSport;
            document.cookie = "selectedTeam=";
            document.getElementById('coachForm').submit();
        }


        function setTeamSelection() {
            var selectedTeam = document.getElementById('teamSelect').value;
            document.cookie = "selectedTeam=" + selectedTeam;
            document.getElementById('coachForm').submit();
        }

        function setRoleSelection() {
            var selectedRole = document.getElementById('roleSelect').value;
            document.cookie = "selectedRole=" + selectedRole;
            document.getElementById('coachForm').submit();
        }

        function getSportSelection() {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i].trim();
                if (cookie.indexOf('selectedSport=') === 0) {
                    var selectedSport = cookie.substring('selectedSport='.length);
                    document.getElementById('sportSelect').value = selectedSport;
                    break;
                }
            }
        }

        function getTeamSelection() {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i].trim();
                if (cookie.indexOf('selectedTeam=') === 0) {
                    var selectedTeam = cookie.substring('selectedTeam='.length);
                    document.getElementById('teamSelect').value = selectedTeam;
                    break;
                }
            }
        }

        function getRoleSelection() {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i].trim();
                if (cookie.indexOf('selectedRole=') === 0) {
                    var selectedRole = cookie.substring('selectedRole='.length);
                    document.getElementById('roleSelect').value = selectedRole;
                    break;
                }
            }
        }
    </script>


<body onload="getSportSelection(); getTeamSelection(); getRoleSelection();">
  <hr>
  <?=$_SESSION["email"]?> is signed In.  <a href="securityCheck.php?log_out=yes">Sign Out</a>
  <hr>
	
  <header>	
  <h1>Lake Forest Sports Program Coach Home Page</h1>
  </header>	
  <header>	
  <h2>Here is your information</h2>
  </header>	

  <?php
    $sqlHead = "SELECT head_team_name, head_pay, Team.team_sport, Team.team_age, Team.team_sex
                FROM Head
                INNER JOIN Team ON head_team_name = Team.team_name
                WHERE head_coach_email = '$email'";
    $resultHead = db_query($sqlHead);

    $sqlAsst = "SELECT asst_team_name, asst_pay, Team.team_sport, Team.team_age, Team.team_sex
                FROM Asst
                INNER JOIN Team ON asst_team_name = Team.team_name
                WHERE asst_coach_email = '$email'";
    $resultAsst = db_query($sqlAsst);
    ?>
 <header>
<h3>Your are the head coach for: <h3>
 </header>	
  <table class = "center">
    <tr>
      <th>Coach Team</th>
      <th>Sport Type</th>
      <th>Sex</th>
      <th>Age </th>
      <th>Pay</th>
    </tr>
    <? while ($head = $resultHead->fetch_assoc()) { ?>
      <tr>
      <td><? echo $head["head_team_name"];?></td>
      <td><? echo $head["team_sport"];?></td>
      <td><? echo $head["team_sex"];?></td>
      <td><? echo $head["team_age"];?></td>
      <td><? echo $head["head_pay"];?></td>
      </tr>
  <? } ?>
    
  </table>
  <br></br>
  
   <header>	
<h3>Your are the assistant coach for:<h3>
 </header>	
  <table class = "center">
    <tr>
      <th>Coach Team</th>
      <th>Sport Type</th>
      <th>Sex</th>
      <th>Age </th>
      <th>Pay</th>
    </tr>
    <? while ($asst = $resultAsst->fetch_assoc()) { ?>
      <tr>
      <td><? echo $asst["asst_team_name"];?></td>
      <td><? echo $asst["team_sport"];?></td>
      <td><? echo $asst["team_sex"];?></td>
      <td><? echo $asst["team_age"];?></td>
      <td><? echo $asst["asst_pay"];?></td>
      </tr>
  <? } ?>    
  
  </table>
  <br></br>
  
  
 <header>	
  <h3>Register to coach a team:</h3>
   </header>	
<form action="coach_welcome.php" method="POST" id="coachForm">
    <table class = "center">
        <tr>
            <th>Sport</th>
            <th>Team</th>
            <th>Role</th>
            <th>Age Group</th>
            <th>Pay</th>
        </tr>

        <tr>
            <td>
            <select name="sport" id="sportSelect" onchange="setSportSelection();">
                    <option value="">Choose Sport</option>
                    <?php
                    $sqlSportSelect = "SELECT sport_type FROM Sport";
                    $resultSportSelect = db_query($sqlSportSelect);

                    while ($sportOption = $resultSportSelect->fetch_assoc()) {
                        echo "<option value=\"" . $sportOption['sport_type'] . "\">" . $sportOption['sport_type'] . "</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
            <select name="team" id="teamSelect" onchange="setTeamSelection();">
                    <option value="">Choose Team</option>
                    <?php
                    if ($selectedSport != '') {
                        $sqlTeamSelect = "SELECT team_name FROM Team WHERE team_sport = '$selectedSport'";
                        $resultTeamSelect = db_query($sqlTeamSelect);

                        while ($teamOption = $resultTeamSelect->fetch_assoc()) {
                            echo "<option value=\"" . $teamOption['team_name'] . "\">" . $teamOption['team_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="role" id="roleSelect" onchange="setRoleSelection();">
                    <option value="">Choose Role</option>
                    <option value="Head Coach">Head</option>
                    <option value="Assistant Coach">Asst</option>
            </td>
            <td>
            <?php
                if ($selectedSport != '' && $selectedTeam != '') {
                    $sqlAgeGroupSelect = "SELECT team_age FROM Team WHERE team_name = '$selectedTeam'";
                    $resultAgeGroup = db_query($sqlAgeGroupSelect);

                    $ageGroup = $resultAgeGroup->fetch_assoc();
                    echo $ageGroup['team_age'];
                } else {
                    echo "";
                }
            ?>

            </td>
            <td>
    <?php
    $sqlHeadPayRole = "SELECT sport_head_coach_pay FROM Sport WHERE sport_type = '$selectedSport'";
    $resultHeadPayRole = db_query($sqlHeadPayRole);
    $headPayRole = $resultHeadPayRole->fetch_assoc();

    $sqlAsstPayRole = "SELECT sport_asst_coach_pay FROM Sport WHERE sport_type = '$selectedSport'";
    $resultAsstPayRole = db_query($sqlAsstPayRole);
    $asstPayRole = $resultAsstPayRole->fetch_assoc();

    if ($selectedRole == "Head Coach") {
        echo $headPayRole['sport_head_coach_pay'];
    } else {
        echo $asstPayRole['sport_asst_coach_pay'];
    }
    ?>
</td>


        </tr>
    </table>
    <input type="submit" name="register" value="Register" style="width: 300px; margin-left: 550px; top: 40px" >
</form>
</body>
</html>

</body>
</html>