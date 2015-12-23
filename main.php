<?php
ob_start();
session_start();

$config_data = parse_ini_file("config.ini");
if($_SERVER['REQUEST_METHOD'] === 'GET') { // a lot of this isn't needed for POST response
?>

<!DOCTYPE html>
<html lang=en>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Udemy Automation Course Companion Site</title>
<link rel="stylesheet" href="css/main.css" media="screen" type="text/css" />

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/jumbotron-narrow.css" rel="stylesheet">

<style type="text/css" media="screen">
#hobby {display: none;}
</style>

<script type="text/javascript" language="javascript">

function showHobbies(clicked){
  if(!clicked) {
    document.getElementById('hobby').style.cssText='display:none';
    document.getElementById('hobby_btn').style.cssText='display:block';
  }
  else {
    document.getElementById('hobby').style.cssText='display:block';
    document.getElementById('hobby_btn').style.cssText='display:none';
  }
}

function addHobby(){
  var hobby_item = document.getElementById('source');
  var hobby = hobby_item.value;

  if(hobby) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
        var count = (xhttp.responseText.match(/failed/g) || []).length;
        if(count <= 0) {
          document.getElementById("hobby_item").innerHTML += "<li>" + xhttp.responseText + "</li>";
          document.getElementById('submit').value = "Add Another Hobby";
          showHobbies(false);
        }
        else {
          showHobbies(false);
        }
      }
    };
    xhttp.open("POST", "main.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("hobby=" + hobby);
  }
  else {
    showHobbies(false);
  }
}

</script>

<script>
  document.createElement('header');
  document.createElement('article');
  document.createElement('footer');
</script>

</head>

<body>
  <header class="navbar navbar-default navbar-static-top">
        <h1>Udemy Automation Course Companion Site</h1>
        <div id="right">
<?php

      if (isset($_SESSION['valid_user'])) {
        echo ('
          <div id="userInfo">Welcome, '.$_SESSION['valid_user'].' | <a href="logout.php">Sign Out</a></div>
        ');
      }
      else { // kick that user out!
        // direct user to login page
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:http://$host$uri/login.php");
      }
?>
     </div>
     <span class="clear"></span>
  </header>
  <div class="container">
  <article class="jumbotron">
    <h2>Hobbies</h2>
    <ul id="hobby_item"></ul> <!-- filled programmatically -->

    <p id="hobby_btn"><input type="submit" name="submit" value="Add Hobby" id="submit" onclick="showHobbies(true);" /><p>
    <div id="hobby">
      <label for="source">Pick a hobby</label>
      <datalist id="sources">
          <select name="source">
<?php
              $hobbies = array("Acting","Antiquing","Backgammon","Backpacking","Badminton","Baseball","Basketball","Board games","Camping","Computer programming","Cycling","Drawing","Gymnastics","Ice skating","Martial arts","Painting","People Watching","Rock climbing","Skiing","Snowboarding","Slacklining","Swimming","Traveling","Trekkie","Triathlon","Video Games","Walking","Weightlifting","Windsurfing","Wrestling","Writing","Yoga","Ziplining","Zumba");
              foreach ($hobbies as $hobby) {
                print ("
                  <option value='$hobby'>$hobby</option>
                  ");
              }
?>
          </select>
      </datalist>
      <input id="source" name="source" list="sources" onblur="addHobby()">
    </div>
  </article>
  <footer>
    <p><a href="faq.php" target="_blank">FAQ</a></p>
  </footer>
</div>
</body>
</html>
<?php
}

// connect to database
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // $db_host = $config_data['db_host'];
        // $db = $config_data['db'];
        // $connection = new PDO("pgsql:host=$db_host;port=5432;dbname=$db", $config_data['db_user'], $config_data['db_pwd']);
        // // $connection = new PDO("mysql:host=$db_host;dbname=$db;charset=utf8", $config_data['db_user'], $config_data['db_pwd']);
        // $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $dbopts = parse_url(getenv('DATABASE_URL'));
        $db_host = $dbopts['host'];
        $db = ltrim($dbopts['path'],'/');
        $connection = new PDO("pgsql:host=$db_host;port=5432;dbname=$db", $dbopts['user'], $dbopts['pass']);
        $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        // get user id
        $ps = $connection->prepare("SELECT id FROM users where username=:username");
        $ps->setFetchMode(PDO::FETCH_OBJ);
        $ps->bindParam(':username', $_SESSION['valid_user']);
        $ps->execute();
        $row = $ps->fetch();
        if(isset($row) && !empty($row) && !empty($row->id)) {
          $_SESSION['user_id'] = $row->id;
        }

        // get existing hobbies to display
        $ps = $connection->prepare("SELECT hobby FROM user_hobbies where user_id=:userid");
        $ps->setFetchMode(PDO::FETCH_OBJ);
        $ps->bindParam(':userid', $_SESSION['user_id']);
        $ps->execute();
        $rows = $ps->fetchAll();
        if(isset($rows) && !empty($rows)) {
          echo ('<script type="text/javascript">
            document.getElementById("submit").value = "Add Another Hobby";
          </script>
            ');
          foreach ($rows as $row) {
            echo ('
            <script type="text/javascript">
              document.getElementById("hobby_item").innerHTML += "<li>" + "'.addslashes($row->hobby).'" + "</li>";
            </script>
            ');
          }
        }
    }
    catch(PDOException $e) {}
}

if (isset($_POST['hobby'])) { // adding a new hobby functionality (called from AJAX request)
  $hobby = trim($_POST['hobby']);
  if(!empty($hobby)) {

    try {
        // $db_host = $config_data['db_host'];
        // $db = $config_data['db'];
        // $connection = new PDO("pgsql:host=$db_host;port=5432;dbname=$db", $config_data['db_user'], $config_data['db_pwd']);
        // // $connection = new PDO("mysql:host=$db_host;dbname=$db;charset=utf8", $config_data['db_user'], $config_data['db_pwd']);
        // $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );


        $dbopts = parse_url(getenv('DATABASE_URL'));
        $db_host = $dbopts['host'];
        $db = ltrim($dbopts['path'],'/');
        $connection = new PDO("pgsql:host=$db_host;port=5432;dbname=$db", $dbopts['user'], $dbopts['pass']);
        $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        // save hobby
        $ps = $connection->prepare("INSERT INTO user_hobbies VALUES (:userid, :hobby, :create_date)");
        $ps->bindParam(':userid', $_SESSION['user_id']);
        $ps->bindParam(':hobby', $hobby);
        $date = date("Y-m-d H:i:s");
        $ps->bindParam(':create_date', $date);
        $ps->execute();
    }
    catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
        exit("failed");
    }

    exit($hobby);
  }
  else {
    exit("failed");
  }
}

?>