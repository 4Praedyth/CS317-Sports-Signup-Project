<?php
// This file does not generate an HTML page.
// Require AFTER init.php file ONLY in Pages that require login security (not signup or login pages)

if ( isset($_GET["log_out"]) ) {
  // Request to Log Out
  // Wipe login session and fall send to login form with a message
  session_start();
  session_destroy();

  header("Location: index.php?message=logged_out");
  exit;
}

if ( ! isset($_SESSION["email"]) ) {
  // OOPS, must be logged in to view this page
  header("Location: index.php?message=not_signed_in");
  exit;
}

if ( $page_access_level == 'admin' && $_SESSION["is_admin"] == False ){
  header("Location: index.php?message=logged_out");
  exit;
}

if ( $page_access_level == 'player' && $_SESSION["is_player"] == False ){
  header("Location: index.php?message=logged_out");
  exit;
}

if ( $page_access_level == 'coach' && $_SESSION["is_coach"] == False ){
  header("Location: index.php?message=logged_out");
  exit;
}





/*if ( $page_access_level == 'admin' && $_SESSION["is_admin"] == False ) {
  // OOPS, this page is for admins only - send to non-admin home page
  if($_SESSION["is_coach"] == False){
    header("Location: player_welcome.php");
    exit;
  }
  else{
    header("Location: coach_welcome.php");
    exit;
  }
}

if ( $page_access_level == 'user' && $_SESSION["is_admin"] == True ) {
   // For simplicity, we'll let admins into user pages, but could easily restrict that.
}*/

?>