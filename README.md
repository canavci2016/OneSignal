# pushNotification
onesignal service push notifications

onesignal.com sunduğu push notificationslarda kullanırız.

client tarafındaki kullanımı;
https://documentation.onesignal.com/docs/web-push-sdk

config.php dosyası tum ayarların yapıldığı dosyadır..


require 'CURL.php';  //sadece curl kutuphanesini kullanır..
require 'OneSignal.php';


$oneSignal = new  OneSignal('CONFIG_PHP_PATH');

ECHO $oneSignal->viewNotifications(2,5);

