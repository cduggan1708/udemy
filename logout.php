<?php
ob_start();
session_start();

?>

<!DOCTYPE html>
<html lang=en>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Udemy Automation Course Companion Site</title>
<link rel="stylesheet" href="css/main.css" media="screen" type="text/css" />
</head>

<body>

<div class="content">

<?php

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$prev_user = $_SESSION['valid_user'];

//log the user out
unset($_SESSION['valid_user']);
session_destroy();

echo '<h2>Log Out</h2>';

if (!empty($prev_user)){
	echo 'Logged Out.<br />
	<div>You will be redirected to the log in page or <a href="login.php">click here</a>.</div>';
	header("Location:http://$host$uri/login.php");
}
else{
	echo 'You were not logged in so log out was unsuccessful.<br />
	<div><a href="login.php">Click here</a> to attempt another log in.</div>';
}

?>



</div>
<!--end div.content-->

</body>
</html>
