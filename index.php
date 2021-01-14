<?php

require 'CURL.php';
require 'OneSignal.php';

$config = require 'config.php';


$oneSignal = new  OneSignal($config['appOd'], $config['restApiKey'], $config['authToken']);
echo "<pre>";
print_r(json_decode($oneSignal->viewNotifications(2, 5), true));
