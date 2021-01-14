<?php

final class OneSignal
{
    private $curl;

    private $appId;
    private $restApiKey;//her bir uygulamanın keyi
    private $authToken; //genel key panelde bulunan

    /**
     * OneSignal constructor.
     * @param $curl
     */
    public function __construct($appId, $restApiKey, $authToken)
    {
        $this->appId = $appId;
        $this->restApiKey = $restApiKey;
        $this->authToken = $authToken;

        $this->curl = CURL::getInstance();
    }

    /**
     * index numarası 0,100,200 ... gider
     * kullanıcıya gönderdiğimiz tum bildirimler
     * */
    public function viewNotifications($index = 0, $limit = 50)
    {
        $headers = ['Authorization: Basic ' . $this->restApiKey];
        return $this->run("https://onesignal.com/api/v1/notifications?app_id={$this->appId}&limit={$limit}&offset={$index}", 'get', [], $headers);
    }

    /**
     * index numarası 0,100,200 ... gider
     * */
    public function viewNotificationById($id)
    {
        $headers = ['Authorization: Basic ' . $this->restApiKey];
        return $this->run("https://onesignal.com/api/v1/notifications/{$id}?app_id={$this->appId}", 'get', [], $headers);
    }

    public function viewDeviceCsvExport($parameter)
    {
        $postFields = array_merge(['extra_fields' => ['location', 'rooted']], $parameter);
        $postFields = json_encode($postFields);
        $headers = ['Content-Type: application/json', 'Authorization: Basic ' . $this->restApiKey];

        return $this->run("https://onesignal.com/api/v1/players/csv_export?app_id={$this->appId}", 'post', $postFields, $headers);
    }

    /**
     * index numarası 0,300,600 ... gider
     * */
    public function viewDevices($index = 0, $limit = 300)
    {
        $headers = ['Authorization: Basic ' . $this->restApiKey];
        return $this->run("https://onesignal.com/api/v1/players?app_id={$this->appId}&limit={$limit}&offset={$index}", 'get', [], $headers);
    }

    /**
     * index numarası 0,300,600 ... gider
     * */
    public function viewDevicesWithId($playerId)
    {
        $headers = ['Authorization: Basic ' . $this->restApiKey];
        return $this->run("https://onesignal.com/api/v1/players/{$playerId}?app_id={$this->appId}", 'get', [], $headers);
    }

    public function viewApps()
    {
        $headers = ['Authorization: Basic ' . $this->authToken];
        return $this->run('https://onesignal.com/api/v1/apps', 'get', [], $headers);
    }

    public function viewAppById($appId = null)
    {
        if (is_null($appId))
            throw  new  Exception('AppId is be required');

        $headers = ["Content-Type: application/json", 'Authorization: Basic ' . $this->authToken];
        return $this->run('https://onesignal.com/api/v1/apps/' . $appId, 'get', [], $headers);
    }

    //tüm kullanıcılara mesaj atar..
    public function sendAllSegments($message)
    {
        return $this->sendMessage($message, ['included_segments' => 'All']);
    }

    //tüm aktif kullanıcılara mesaj atar.
    public function sendActiveSegments($message)
    {
        return $this->sendMessage($message, ['included_segments' => 'Active Users']);
    }

    //tüm pasif kullanıcılara mesaj atar.
    public function sendInActiveSegments($message)
    {
        return $this->sendMessage($message, ['included_segments' => 'Inactive Users']);
    }

    //tüm aktif olmayan kullanıcılara mesaj atar.
    public function sendEngadedSegments($message)
    {
        return $this->sendMessage($message, ['included_segments' => 'Engaged Users']);
    }

    /**
     * seçili socketid lerine mesaj atar..
     * */
    public function sendPlayerWithId($message, $player_Ids = [])
    {
        return $this->sendMessage($message, ['include_player_ids' => $player_Ids]);
    }

    private function sendMessage($message = 'default', array $parameter = [])
    {
        $postFields = array_merge(['app_id' => $this->appId, 'contents' => ['en' => $message]], $parameter);
        $postFields = json_encode($postFields);

        $headers = ['Content-Type: application/json', 'Authorization: Basic ' . $this->restApiKey];
        return $this->run("https://onesignal.com/api/v1/notifications", 'post', $postFields, $headers);
    }

    private function run($url, $method, $parameter = [], $headers = [])
    {
        $this->curl->setUrl($url);
        $this->curl->setHeader($headers);
        if ($method == 'get') {
            return $this->curl->execute($parameter, false);
        } else {
            return $this->curl->execute($parameter, true);
        }
    }

}