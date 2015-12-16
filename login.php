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

$config_data = parse_ini_file("config.ini");

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if ((isset($_POST['username'])) && (isset($_POST['password']))) {
  
      $username = $_POST['username'];
      $password = md5($_POST['password']);

      // remember me
      $year = time() + 31536000;
      if(!empty($_POST['remember'])) {
          setcookie('remember_me', $_POST['username'], $year);
      }
      else {
          if(!empty($_COOKIE['remember_me'])) {
              $past = time() - 100;
              setcookie('remember_me', '', $past);
          }
      }
  
      // connect to database
      try {
          $db_host = $config_data['db_host'];
          $db = $config_data['db'];
          $connection = new PDO("mysql:host=$db_host;dbname=$db;charset=utf8", $config_data['db_user'], $config_data['db_pwd']);
          $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

          $ps = $connection->prepare("SELECT `username`, `password` FROM users where `username`=:username and `password`=:password");
          $ps->setFetchMode(PDO::FETCH_OBJ);
          $ps->bindParam(':username', $username);
          $ps->bindParam(':password', $password);
          $ps->execute();
          $row = $ps->fetch();
      }
      catch(PDOException $e) {
          echo 'ERROR: ' . $e->getMessage();
      }

      if(isset($row) && $row != null) {
        	//if in database, register username
        	$_SESSION['valid_user'] = $username;
        	echo ('
        	You are logged in as '.$_SESSION['valid_user'].'.<br /><br />
        	You will be directed to the main page or click <a href="main.php">here</a>.
        	');

          // remove failed cookie
          $past = time() - 100;
          setcookie('failed', '', $past);
          
          header("Location:http://$host$uri/main.php");
      }
      else {
          setcookie('failed', 1);
          header("Location:http://$host$uri/login.php");
      }
  
} else if (empty($_SESSION['valid_user'])) {
      $checked = "";
      $un = "";
      if(!empty($_COOKIE['remember_me'])) {
             $checked = "checked";
             $un = $_COOKIE['remember_me'];
      }

      if(!empty($_COOKIE['failed'])) {
             echo '<br /><div class="note">Login failed.</div><br />';
      }
      echo '<form method="post" action="">';
      echo ('
 
       <p style="font-weight: bold; font-size: 1.5em;">Log in or <a href="register.php">register</a>.</p>
       
       <table>
       <tr><td>Username:</td>
       <td><input type="text" name="username" id="username" value="'.$un.'"/></td></tr>
       <tr><td>Password:</td>
       <td><input type="password" name="password" id="password"/></td></tr>
       <tr><td><input type="checkbox" name="remember" id="remember" '.$checked.'>Remember Me</td></tr>
       <tr><td colspan="2" align="center"><input type="submit" value="Log in" id="submit"/></td></tr>
       </table>
       </form>
       
       ');

      // page refresh should not show Login failed
      setcookie('failed', 0);
       
} //end else if
  
else {
  
      echo 'You are already logged in as '.$_SESSION['valid_user'].'.<br />
      
      You will be directed to the main page or click <a href="main.php">here</a>.';
      header("Location:http://$host$uri/main.php");
      
} //end else

?>

</div>
<!--end div.content-->

</body>
</html>
