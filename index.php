<?php

require 'CURL.php';
require 'OneSignal.php';


$oneSignal = new  OneSignal('config.php');
echo  "<pre>";
print_r(json_decode($oneSignal->viewNotifications(2,5),true));
