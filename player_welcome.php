<?php
require_once 'init.php';

//$page_access_level = 'user'; // set to 'user' or 'admin' before including security check
//require_once 'securityCheck.php'; // Must be logged in to get past here

$page_access_level = 'player';
require_once 'securityCheck.php'; // Must be logged in to get past here
$email = $_SESSION["email"];
$password = $_SESSION["password"];



if (isset($_POST['chooseUniforms'])) { //NEED TO MAKE UNIFORM SELECTION POPUP/PAGE???
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

  $selectedSport = $_POST['sport'];
  $selectedTeam = $_POST['team'];
  $selectedAgeGroup = $_POST['ageGroup'];
  $jerseyQuantity = (int)$_POST['jerseys'];
  $shortsQuantity = (int)$_POST['shorts'];

  // Calculate uniform cost
  $sqlJerseyCost = "SELECT unifProfile_jersey_cost
                  FROM UnifProfile
                  WHERE unifProfile_id = (SELECT sport_unif_prof_id FROM Sport WHERE sport_type = '$selectedSport')";
$resultJerseyCost = db_query($sqlJerseyCost);
$jerseyCostRow = $resultJerseyCost->fetch_assoc();


$jerseyCostPerItem = (int)$jerseyCostRow['unifProfile_jersey_cost'];



$sqlShortsCost = "SELECT unifProfile_shorts_cost
                  FROM UnifProfile
                  WHERE unifProfile_id = (SELECT sport_unif_prof_id FROM Sport WHERE sport_type = '$selectedSport')";
$resultShortsCost = db_query($sqlShortsCost);
$shortsCostRow = $resultShortsCost->fetch_assoc();
$shortsCostPerItem = (int)$shortsCostRow['unifProfile_shorts_cost'];

$jerseyCostTotal = $jerseyQuantity * $jerseyCostPerItem;
$shortsCostTotal = $shortsQuantity * $shortsCostPerItem;

    // Insert or update player information
    $email = $_SESSION["email"];
    $password = $_SESSION["password"];

    $sqlInsertOrUpdatePlayerInfo = "INSERT INTO Player (player_email, player_password, player_sport, player_team, player_uni_fee)
                            VALUES ('$email', '$password', '$selectedSport', '$selectedTeam', $jerseyCostTotal + $shortsCostTotal)
                            ON DUPLICATE KEY UPDATE 
                            player_sport = CONCAT(player_sport, ',', '$selectedSport'), 
                            player_team = '$selectedTeam',
                            player_uni_fee = $jerseyCostTotal + $shortsCostTotal";

db_query($sqlInsertOrUpdatePlayerInfo); 

  // Update fee with uniform cost
  $sqlUpdateFee = "UPDATE Player
                SET player_uni_fee = $jerseyCostTotal + $shortsCostTotal
                WHERE player_email = '$email' AND player_password = '$password'";

db_query($sqlUpdateFee);

  $sqlUpdatedFee = "SELECT UnifProfile_id, sum(unifProfile_jersey_cost*unifProfile_jersey_min) + sum(unifProfile_shorts_cost*unifProfile_shorts_min) + player_uni_fee AS Fee
                 FROM Player
                 LEFT JOIN Sport ON player_sport = Sport.sport_type
                 LEFT JOIN UnifProfile ON sport_unif_prof_id = UnifProfile.unifProfile_id
                 WHERE player_email = '$email' AND player_password = '$password'
                 GROUP BY UnifProfile_id";

  $resultUpdatedFee = db_query($sqlUpdatedFee);
  $updatedFee = $resultUpdatedFee->fetch_assoc()['Fee'];
}




if (isset($_COOKIE['selectedSport'])) {
  $selectedSport = $_COOKIE['selectedSport'];
}

if (isset($_COOKIE['selectedSport']) && isset($_COOKIE['selectedTeam'])) {
  $selectedTeam = $_COOKIE['selectedTeam'];
}


?>
<!DOCTYPE html>
<html>
<head><title>Welcome to the Lake Forest Sports Program</title>
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

header h3 {
  text-align: center;
  font-size: 30px;
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



        /* Your existing styles */
        table, th, td {
            border: 1px solid black;
            text-align: center;
            height: 30px;
            width: 50%;
            padding: 10px;
            vertical-align: bottom;
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

        
    /* Add this style to set the width of the input fields */
    input[type="number"] {
        width: 50px; /* You can adjust the width as needed */
    }

    /* Add this style to center the input fields */
    input[type="number"] {
        margin: 0 auto;
        display: block;
    }

        
select {
    width: 100%; /* Set the width to 100% of the parent element */
    padding: 10px; /* Add some padding for better appearance */
    box-sizing: border-box; /* Include padding and border in the total width */
}




input[type="submit"] {
    width: 50%; /* Set the width to 100% of the parent element */
    padding: 16px 20px; /* Adjust padding as needed */
    box-sizing: border-box; /* Include padding and border in the total width */
}






    </style>
</head>
<script>

function playeropenForm() {
    var formPopup = document.getElementById('uniformForm');
    formPopup.style.display = 'block';
  }

  function playercloseForm() {
    var formPopup = document.getElementById('uniformForm');
    formPopup.style.display = 'none';
  }

function setSportSelection() {
    var selectedSport = document.getElementById('sportSelect').value;

    // Set the cookie with the selected sport
    document.cookie = "selectedSport=" + selectedSport;

    // Submit the form
    document.getElementById('teamForm').submit();
    }

    function setTeamSelection() {
      var selectedTeam = document.getElementById('teamSelect').value;

      // Set the cookie with the selected sport
      document.cookie = "selectedTeam=" + selectedTeam;

      // Submit the form
      document.getElementById('teamForm').submit();
    }

//alert("Selected Sport: " + selectedSport); // Add this line for debugging

function getSportSelection() {
    // Retrieve the selected sport from the cookie
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
    // Retrieve the selected sport from the cookie
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




</script>
<style>
table, th, td {
  border:1px solid black;
}
</style>


<body onload="getSportSelection(); getTeamSelection();">
<hr>
<?=$email?> is Signed In.  <a href="securityCheck.php?log_out=yes">Sign Out</a>
<hr>

<header>
<h1>Lake Forest Sports Program Player Home Page</h1>
</header>
<header>
<h3>Here is your information</h3>
</header>


<?php

    $sqlPlayer = "SELECT player_sport FROM Player 
                  WHERE player_email = '$email' AND player_password = '$password'";

    $resultPlayer = db_query($sqlPlayer);
    $sport = $resultPlayer->fetch_assoc();


    $sqlTeam = "SELECT player_team FROM Player 
                WHERE player_email = '$email' AND player_password = '$password'";

    $resultTeam = db_query($sqlTeam);
    $team = $resultTeam->fetch_assoc();
    


    $sqlPlayerFee = "SELECT UnifProfile_id, sum(unifProfile_jersey_cost*unifProfile_jersey_min) + sum(unifProfile_shorts_cost*unifProfile_shorts_min) + player_uni_fee AS Fee
                 FROM Player
                 LEFT JOIN Sport ON player_sport = Sport.sport_type
                 LEFT JOIN UnifProfile ON sport_unif_prof_id = UnifProfile.unifProfile_id
                 WHERE player_email = '$email' AND player_password = '$password'
                 GROUP BY UnifProfile_id";


    $resultFee = db_query($sqlPlayerFee);
    $fee = $resultFee->fetch_assoc();



  ?>



<table class = "center">
  <tr>
    <th>Sport</th>
    <th>Team</th>
    <th>Head Coach</th>
    <th>Assistant Coach</th>
    <th>Enrollment Fee</th>
    <th>Total Uniform Fee</th>
  </tr> 
  <?php
  


  $playerSports = explode(",", $sport["player_sport"]);
  foreach ($playerSports as $selectedSportDisplay) {    
    // Prepare queries for each sport
    $sqlTeamSelect2 = "SELECT team_name FROM Team WHERE team_sport = '$selectedSportDisplay'";

//ISSUE LIES HERE
    $sqlHeadCoach = "SELECT coach_name
                      FROM Coach 
                      JOIN Head ON Coach.coach_email = Head.head_coach_email 
                      WHERE Head.head_team_name = '$team[player_team]'";


$resultHeadCoach = db_query($sqlHeadCoach); 
$headCoach = $resultHeadCoach->fetch_assoc()['coach_name'];


//ISSUE LIES HERE
$sqlAsstCoach = "SELECT coach_name
                  FROM Coach 
                  JOIN Asst ON Coach.coach_email = Asst.asst_coach_email 
                  WHERE Asst.asst_team_name = '$team[player_team]'";
$resultAsstCoach = db_query($sqlAsstCoach);
$asstCoach = $resultAsstCoach->fetch_assoc()['coach_name'];



    $sqlAgeGroupSelect2 = "SELECT team_age FROM Team WHERE team_name = '{$team["player_team"]}'";
    $sqlPlayerFee2 = "SELECT UnifProfile_id,
              unifProfile_jersey_cost * unifProfile_jersey_min + unifProfile_shorts_cost * unifProfile_shorts_min + sport_player_fee AS Fee
              FROM Player
              LEFT JOIN Sport ON player_sport = Sport.sport_type
              LEFT JOIN UnifProfile ON sport_unif_prof_id = UnifProfile.unifProfile_id
              WHERE LOWER(Sport.sport_type) = LOWER('$selectedSportDisplay')";
    
    

    // Execute queries and fetch results
    $resultTeam2 = db_query($sqlTeamSelect2);
    $resultAgeGroup2 = db_query($sqlAgeGroupSelect2);
    $resultFee2 = db_query($sqlPlayerFee2);

    

    // Extract data from results
    $teamName2 = $resultTeam2->fetch_assoc()['team_name'];
    $ageGroup2 = $resultAgeGroup2->fetch_assoc()['team_age'];
    $fee2 = $resultFee2->fetch_assoc()['Fee'];
    


    // Display each sport in a separate row if there is data
    if ($teamName2 || $ageGroup2 || $fee2 || $headCoach || $asstCoach) {
      echo "<tr>";
      echo "<td>$selectedSportDisplay</td>";
      echo "<td>$teamName2</td>";
      echo "<td>$headCoach</td>";
      echo "<td>$asstCoach</td>";
      echo "<td>$$fee2</td>";

      
    }
    
// Fetch and display the total uniform fee
$totalUniformFeeQuery = "SELECT player_uni_fee FROM Player 
WHERE player_email = '$email' AND player_password = '$password'";

$resultTotalUniformFee = db_query($totalUniformFeeQuery);
$totalUniformFee = $resultTotalUniformFee->fetch_assoc()['player_uni_fee'];


  }
$totalUniformFeeQuery = "SELECT player_uni_fee FROM Player 
WHERE player_email = '$email' AND player_password = '$password'";

$resultTotalUniformFee = db_query($totalUniformFeeQuery);
$totalUniformFee = $resultTotalUniformFee->fetch_assoc()['player_uni_fee'];

if($totalUniformFee > 0){
  echo "<td>$$totalUniformFee</td>";
}

  $totalUniformFeeQuery = "SELECT SUM(unifProfile_jersey_cost * unifProfile_jersey_min +
  unifProfile_shorts_cost * unifProfile_shorts_min +
  sport_player_fee) AS TotalFee
FROM Player
LEFT JOIN Sport ON player_sport = Sport.sport_type
LEFT JOIN UnifProfile ON sport_unif_prof_id = UnifProfile.unifProfile_id
WHERE player_email = '$email' AND player_password = '$password'";

$resultTotalUniformFee = db_query($totalUniformFeeQuery);
$totalUniformFee = $resultTotalUniformFee->fetch_assoc()['TotalFee'];
  ?>
</table>

<header>
<h3>Register for a team:</h3>
</header>
<form action="player_welcome.php" method="POST" id="teamForm">
    <table class = "center">
        <tr>
            <th>Sport</th>
            <th>Team</th>
            <th>Age Group</th>
            <th>Number of Jerseys</th>
            <th>Number of Shorts</th>
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
                <?php
                if ($selectedSport != '' && $selectedTeam != '') {
                    $sqlAgeGroupSelect = "SELECT team_age FROM Team WHERE team_name = '$selectedTeam'";
                    $resultAgeGroup = db_query($sqlAgeGroupSelect);

                    while ($ageGroup = $resultAgeGroup->fetch_assoc()) {
                        echo $ageGroup['team_age'];
                    }
                }
                ?>
            </td>
            <td>
                <input type="number" name="jerseys" id="jerseys" value="1" min="1">
            </td>
            <td>
                <input type="number" name="shorts" id="shorts" value="1" min="1">
            </td>
            </tr>
        </table>
        <input type="submit" name="register" value="Register" style="width: 50%; margin-left: 350px; top: 40px" >
    </form>
    </form>
</div>
</body>

</html>



<?

?>
