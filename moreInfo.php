<?php
require_once 'init.php';

$page_access_level = 'user'; // set to 'user' or 'admin' before including security check
require_once 'securityCheck.php';  // Must be logged in to get past here

if ( !isset($_GET["WorkID"]) ) {
  // gotta have incoming WorkID to be on this page
  header("Location: searchPaintings.php");
  exit;
}

$WorkID = $_GET["WorkID"]; // no need to trim because WorkID is database PK

$sql = "SELECT * FROM vrg_work, vrg_artist
           WHERE vrg_work.ArtistID=vrg_artist.ArtistID AND WorkID='$WorkID' ";

//var_dump($sql);exit;
$result = db_query($sql);

// Only one row to fetch since WorkID is PK from vrg_work table
$row = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head><title>Painting Details</title></head>

<body>
<hr>
<?=$_SESSION["fullname"]?> is Signed In.  <a href="securityCheck.php?log_out=yes">Sign Out</a>
<br><br>
<a href='#null' onclick="history.back()">Back to Search Results</a>
<hr>

<h1>Work Details</h1>

<table border='1' cellpadding="5">
  <tr>
    <td><b>WorkID</b></td>
    <td><b>Title</b></td>
    <td><b>Copy</b></td>
    <td><b>Medium</b></td>
    <td><b>Description</b></td>
    <td><b>Artist</b></td>
  </tr>
  <tr>
    <td><?=$row["WorkID"]?></td>
    <td><?=$row["Title"]?></td>
    <td><?=$row["Copy"]?></td>
    <td><?=$row["Medium"]?></td>
    <td><?=$row["Description"]?></td>
    <td><?=$row["FirstName"]?> <?=$row["LastName"]?></td>
  </tr>
</table>


</body>
</html>