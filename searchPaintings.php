<?php
require_once 'init.php';

$page_access_level = 'user'; // set to 'user' or 'admin' before including security check
require_once 'securityCheck.php'; // Must be logged in to get past here

// Search field values will initially be empty
$title = '';
$artist = '';
$nationality = '';


if ( isset($_GET["title"]) ) {
  $title = trim($_GET["title"]);
  $artist = trim($_GET["artist"]);
  $nationality = trim($_GET["nationality"]);

  $search_where_clause = "1";

  if ($title != '') {
    $search_where_clause .= " AND Title LIKE '%$title%' ";
  }
  if ($artist != '') {
    $search_where_clause .= " AND (FirstName LIKE '%$artist%' OR LastName LIKE '%$artist%') ";
  }
  if ($nationality != '') {
    $search_where_clause .= " AND Nationality LIKE '%$nationality%' ";
  }

  $sql = "SELECT WorkID, Title, Firstname, Lastname, Nationality FROM vrg_work, vrg_artist
                  WHERE vrg_work.ArtistID=vrg_artist.ArtistID AND $search_where_clause ";

  //var_dump($sql);exit;
  $result = db_query($sql);

  if ($result->num_rows == 0) {
    $message = 'No paintings Matched your Search Criteria.';
    unset($result);
  }

}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Search for a Painting</title>
  <style>
    table, th, td, tr {
      border: 1px solid grey;
      border-collapse:collapse;
    }
  </style>
</head>
<body>
<hr>
<?=$_SESSION["fullname"]?> is Signed In.  <a href="securityCheck.php?log_out=yes">Sign Out</a>
<br><br>
<a href='welcome.php'>Members Home Page</a>
<hr>

<h1>Search for a Painting</h1>
<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
    Title: <input type="text" name="title"  value="<?= $title ?>">
    <br><br>
    Artist: <input type="text" name="artist" value="<?= $artist ?>" >
    <br><br>
    Nationality: <input type="text" name="nationality" value="<?= $nationality ?>">
    <br><br>
    <input type="submit" value="Search">
    &nbsp;&nbsp;
    <input type="reset" value="Clear">
</form>

<? if (isset($message)) { ?>
    <h6><?= $message ?></h6>
<? } ?>

<? if (isset($result)) { ?>

  <h2>Search Results</h2>

  <table border='1'>
  <tr>
    <td><b>Title</b></td>
    <td><b>Firstname</b></td>
    <td><b>Lastname</b></td>
    <td><b>Nationality</b></td>
  </tr>

  <? while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><a href='moreInfo.php?WorkID=<?=$row["WorkID"]?>' ><?=$row["Title"]?></a></td>
        <td><?=$row["Firstname"]?></td>
        <td><?=$row["Lastname"]?></td>
        <td><?=$row["Nationality"]?></td>
      </tr>
  <? } ?>

  </table>
<? } ?>


</body>
</html>