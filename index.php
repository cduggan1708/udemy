<?php

require('vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('app.log', Logger::DEBUG));

$dbopts = parse_url(getenv('DATABASE_URL'));
print_r($dbopts);
	
?>

<!DOCTYPE html>
<html lang=en>
  <head>
    <meta http-equiv="Refresh" content="0; url=login.php">
  </head>
  <body>
  </body>
</html>