# pushNotification
onesignal service push notifications

we use the notficiation services which is served by onesignal

on client side usage:
https://documentation.onesignal.com/docs/web-push-sdk

as shown below. we just need following ambigious three fields then we might be able to test whether or not class works

require 'CURL.php'; 

require 'OneSignal.php';

$oneSignal = new  OneSignal(APP_ID, API_KEY,AUTH_TOKEN);

ECHO $oneSignal->viewNotifications(2,5);

