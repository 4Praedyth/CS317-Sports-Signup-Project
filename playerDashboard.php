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
  $jerseyCount = $_POST['jerseyCount'];
  $shortsCount = $_POST['shortsCount'];

  // Calculate uniform cost
  $sqlUniformCost = "SELECT unifProfile_jersey_cost, unifProfile_shorts_cost
                     FROM UnifProfile
                     WHERE unifProfile_id = (SELECT sport_unif_prof_id FROM Sport WHERE sport_type = '$selectedSport')";

  $resultUniformCost = db_query($sqlUniformCost);
  $uniformCost = $resultUniformCost->fetch_assoc();

  $jerseyCost = $jerseyCount * $uniformCost['unifProfile_jersey_cost'];
  $shortsCost = $shortsCount * $uniformCost['unifProfile_shorts_cost'];

  // Insert data or update if the email already exists
  $email = $_SESSION["email"];
  $password = $_SESSION["password"];

  $sqlInsertPlayerInfo = "INSERT INTO Player (player_email, player_password, player_sport, player_team)
                          VALUES ('$email', '$password', '$selectedSport', '$selectedTeam')
                          ON DUPLICATE KEY UPDATE 
                          player_sport = CONCAT(player_sport, ',', '$selectedSport'), 
                          player_team = '$selectedTeam'";

  db_query($sqlInsertPlayerInfo);

  // Update fee with uniform cost
  $sqlUpdateFee = "UPDATE Player
                   SET player_uni_fee = $jerseyCost + $shortsCost
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

        .open-button-choose-uniforms {
            
            padding: 16px 20px;
            cursor: pointer;
            margin: 0 auto; /* Center the button horizontally */
            display: block; /* Make it a block element */
            width: 100%; /* Set the width to 100% of the parent element */

            }
            .form-popup-player {
    display: none;
    position: fixed;
    bottom: 0;
    right: 15px;
    border: 1px solid #ccc;
    z-index: 9;
    max-width: 300px;
    padding: 10px;
    background-color: white;
  }

  /* Add styles to the form container */
  .form-container-player {
    max-width: 100%;
    padding: 10px;
  }

  /* Full-width input fields */
  .form-container-player input[type=text] {
    width: 100%;
    padding: 10px;
    margin: 5px 0 10px 0;
    border: 1px solid #ccc;
  }

  /* Set a style for the submit button */
  .form-container-player .btn {
    background-color: #04AA6D;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
  }

  /* Add some hover effects to buttons */
  .form-container-player .btn:hover {
    opacity: 0.8;
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
.form-popup-player {
    display: none;
    position: absolute;
    top: 30%; /* Adjust the top percentage as needed */
    right: 20%; /* Adjust the right percentage as needed */
    max-height: 40vh; /* Adjust the maximum height as needed */
    border: 1px solid #ccc;
    z-index: 9;
    width: 300px; /* Adjust the width as needed */
    padding: 10px;
    background-color: white;
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
    
    $sqlHeadCoach = "SELECT coach_name FROM Coach 
                      JOIN Head ON Coach.coach_email = Head.head_coach_email 
                      JOIN Player ON Head.head_team_name = Player.player_team 
                      WHERE player_email = '$email' AND player_password = '$password'";
    
    $resultHeadCoach = db_query($sqlHeadCoach);
    $headCoach = $resultHeadCoach->fetch_assoc();

    $sqlAsstCoach = "SELECT coach_name FROM Coach 
                    JOIN Asst ON Coach.coach_email = Asst.asst_coach_email 
                    JOIN Player ON Asst.asst_team_name = Player.player_team 
                    WHERE player_email = '$email' AND player_password = '$password'";
    $resultAsstCoach = db_query($sqlAsstCoach);
    $asstCoach = $resultAsstCoach->fetch_assoc();

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
    <th>Fee</th>
  </tr> 
  <?php
  // Loop through the player's sports
  $playerSports = explode(",", $sport["player_sport"]);
  foreach ($playerSports as $selectedSportDisplay) {    // Use $selectedSportDisplay instead of $selectedSport
    // Prepare queries for each sport
    $sqlTeamSelect2 = "SELECT team_name FROM Team WHERE team_sport = '$selectedSportDisplay'";
    $sqlHeadCoach2 = "SELECT coach_name FROM Coach 
                      JOIN Head ON Coach.coach_email = Head.head_coach_email 
                      JOIN Player ON Head.head_team_name = Player.player_team 
                      JOIN Sport ON Sport.sport_type = Player.player_sport
                      WHERE Sport.sport_type = '$selectedSportDisplay'";
    $sqlAsstCoach2 = "SELECT coach_name FROM Coach 
                      JOIN Asst ON Coach.coach_email = Asst.asst_coach_email 
                      JOIN Player ON Asst.asst_team_name = Player.player_team 
                      JOIN Sport ON Sport.sport_type = Player.player_sport
                      WHERE Sport.sport_type = '$selectedSportDisplay'";
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
    $resultHeadCoach2 = db_query($sqlHeadCoach2);
    $resultAsstCoach2 = db_query($sqlAsstCoach2);

    // Extract data from results
    $teamName2 = $resultTeam2->fetch_assoc()['team_name'];
    $ageGroup2 = $resultAgeGroup2->fetch_assoc()['team_age'];
    $fee2 = $resultFee2->fetch_assoc()['Fee'];
    $headCoach2 = $resultHeadCoach2->fetch_assoc()['coach_name'];
    $asstCoach2 = $resultAsstCoach2->fetch_assoc()['coach_name'];

    // Display each sport in a separate row if there is data
    if ($teamName2 || $ageGroup2 || $fee2 || $headCoach2 || $asstCoach2) {
      echo "<tr>";
      echo "<td>$selectedSportDisplay</td>";
      echo "<td>$teamName2</td>";
      echo "<td>$headCoach2</td>";
      echo "<td>$asstCoach2</td>";
      echo "<td>$$fee2</td>";
      echo "</tr>";
    }
  }
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
            <th>Uniforms</th>
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
                    <button type="button" class="btn" onclick="playeropenForm()">Select Uniforms</button>
                </td>
            </tr>
        </table>
        <input type="submit" name="register" value="Register" style="width: 300px; margin-left: 550px; top: 40px" >
    </form>

    <!-- Move the uniform form outside the table structure -->
    <div class="form-popup-player" id="uniformForm">
    <form action="your_uniform_processing_script.php" method="post" class="form-container-player">
        <!-- Add your uniform selection options or input fields here -->

        <!-- Add input fields for the number of jerseys and shorts -->
        <label for="jerseyQuantity">Number of Jerseys:</label>
        <input type="number" id="jerseyQuantity" name="jerseyQuantity" min="1" value="1" required>
        <br><br>
        <label for="shortsQuantity">Number of Shorts:</label>
        <input type="number" id="shortsQuantity" name="shortsQuantity" min="1" value="1" required>

        <button type="button" class="btn" onclick="playercloseForm()">Close</button>
    </form>
</div>
</body>

</html>



<?

?>
