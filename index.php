<?php

require('vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('app.log', Logger::DEBUG));

$dbopts = parse_url(getenv('DATABASE_URL'));
$db_host = $dbopts['host'];
$db = ltrim($dbopts['path'],'/');
$app->register(new PDO("pgsql:host=$db_host;port=5432;dbname=$db", $dbopts['user'], $dbopts['pass']));
	
?>

<!DOCTYPE html>
<html lang=en>
  <head>
    <meta http-equiv="Refresh" content="0; url=login.php">
  </head>
  <body>
  </body>
</html>