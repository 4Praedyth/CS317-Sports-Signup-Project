<?php
require_once 'init.php';

//$page_access_level = 'user'; // set to 'user' or 'admin' before including security check
//require_once 'securityCheck.php'; // Must be logged in to get past here

?>
<!DOCTYPE html>
<html>
<head><title>Welcome to the Lake Forest Sports Program</title></head>

<body>
<hr>
<?=$_SESSION["fullname"]?> is Signed In.  <a href="securityCheck.php?log_out=yes">Sign Out</a>
<hr>

<h1>Lake Forest Sports Program Home Page</h1>

<ul>
</ul>

</body>
</html>