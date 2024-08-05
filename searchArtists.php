<?php
require_once 'init.php';

$page_access_level = 'user'; // set to 'user' or 'admin' before including security check
require_once 'securityCheck.php'; // Must be logged in to get past here

if ( isset($_GET["artist"]) ) {
  $artist = trim($_GET["artist"]);
  $nationality = trim($_GET["nationality"]);

  $search_where_clause = "1";

   if ($artist != '') {
    $search_where_clause .= " AND (FirstName LIKE '%$artist%' OR LastName LIKE '%$artist%') ";
  }
  if ($nationality != '') {
    $search_where_clause .= " AND Nationality LIKE '%$nationality%' ";
  }

  $sql = "SELECT * FROM vrg_artist
                  WHERE $search_where_clause ";

  //var_dump($sql);exit;
  $result = db_query($sql);

  if ($result->num_rows == 0) {
    $message = 'No Artists Matched your Search Criteria.';
    unset($result);
  }

}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Search for an Artist</title>
</head>
<body>
<hr>
<?=$_SESSION["fullname"]?> is Signed In.  <a href="securityCheck.php?log_out=yes">Sign Out</a>
<br><br>
<a href='welcome.php'>Members Home Page</a>
<hr>


<h1>Search Artists from the Database</h1>
<form action="searchArtists.php"  method="GET" >
    Artist Name:  <input type="text" name="artist">
    <br><br>
    Nationality: <input type="text" name="nationality">
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

  <table>
    <tr>
      <th>ArtistID</th>
      <th>FirstName</th>
      <th>LastName</th>
      <th>DOB</th>
      <th>DOD</th>
      <th>Nationality</th>
    </tr>

<? while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?=$row['ArtistID']?></td>
        <td><?=$row['FirstName']?></td>
        <td><?=$row['LastName']?></td>
        <td><?=$row['DateOfBirth']?></td>
        <td><?=$row['DateDeceased']?></td>
        <td><?=$row['Nationality']?></td>
      </tr>
  <? } ?>

  </table>
<? } ?>


</body>
</html>






