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
    public function __construct($configPath = 'config.php')
    {
        if (!file_exists($configPath))
            throw  new Exception($configPath . ' file not found please try again');

        $data = require $configPath;
        $this->appId = $data['appId'];
        $this->restApiKey = $data['restApiKey'];
        $this->authToken = $data['authToken'];

        $this->curl = CURL::getInstance();
    }


    /*
     *
     * index numarası 0,100,200 ... gider
     *
     * kullanıcıya gönderdiğimiz tum bildirimler
     * */
    public function viewNotifications($index = 0, $limit = 50)
    {
        $this->curl->setUrl("https://onesignal.com/api/v1/notifications?app_id={$this->appId}&limit={$limit}&offset={$index}");
        $this->curl->setHeader([
            'Authorization: Basic ' . $this->restApiKey
        ]);

        return $this->curl->execute([], false);
    }


    /*
   *
   * index numarası 0,100,200 ... gider
   * */
    public function viewNotificationById($id)
    {
        $this->curl->setUrl("https://onesignal.com/api/v1/notifications/{$id}?app_id={$this->appId}");
        $this->curl->setHeader([
            'Authorization: Basic ' . $this->restApiKey
        ]);

        return $this->curl->execute([], false);
    }


    public function viewDeviceCsvExport()
    {
        $this->curl->setUrl("https://onesignal.com/api/v1/players/csv_export?app_id={$this->appId}");
        $this->curl->setHeader([
            'Content-Type: application/json',
            'Authorization: Basic ' . $this->restApiKey
        ]);

        $postFields = [
            'extra_fields' => [
                'location',
                'rooted'
            ]
        ];

        if (!empty($parameter))
            $postFields = array_merge($postFields, $parameter);

        $postFields = json_encode($postFields);


        return $this->curl->execute($postFields, true);
    }


    /*
     *
     * index numarası 0,300,600 ... gider
     * */
    public function viewDevices($index = 0, $limit = 300)
    {
        $this->curl->setUrl("https://onesignal.com/api/v1/players?app_id={$this->appId}&limit={$limit}&offset={$index}");
        $this->curl->setHeader([
            'Authorization: Basic ' . $this->restApiKey
        ]);

        return $this->curl->execute([], false);
    }


    /*
     *
     * index numarası 0,300,600 ... gider
     * */
    public function viewDevicesWithId($playerId)
    {
        $this->curl->setUrl("https://onesignal.com/api/v1/players/{$playerId}?app_id={$this->appId}");
        $this->curl->setHeader([
            'Authorization: Basic ' . $this->restApiKey
        ]);
        return $this->curl->execute([], false);
    }


    public function viewApps()
    {
        $this->curl->setUrl('https://onesignal.com/api/v1/apps');
        $this->curl->setHeader([
            'Authorization: Basic ' . $this->authToken
        ]);

        return $this->curl->execute([], false);
    }

    public function viewAppById($appId = null)
    {
        if (is_null($appId))
            throw  new  Exception('AppId is be required');


        $this->curl->setUrl('https://onesignal.com/api/v1/apps/' . $appId);
        $this->curl->setHeader([
            "Content-Type: application/json",
            'Authorization: Basic ' . $this->authToken
        ]);

        return $this->curl->execute([], false);
    }


    //tüm kullanıcılara mesaj atar..
    public function sendAllSegments($message)
    {
        $parameter = [
            'included_segments' => 'All'
        ];
        return $this->sendMessage($message, $parameter);
    }

    //tüm aktif kullanıcılara mesaj atar.
    public function sendActiveSegments($message)
    {
        $parameter = [
            'included_segments' => 'Active Users'
        ];
        return $this->sendMessage($message, $parameter);
    }

    //tüm pasif kullanıcılara mesaj atar.
    public function sendInActiveSegments($message)
    {
        $parameter = [
            'included_segments' => 'Inactive Users'
        ];
        return $this->sendMessage($message, $parameter);
    }

    //tüm aktif olmayan kullanıcılara mesaj atar.
    public function sendEngadedSegments($message)
    {
        $parameter = [
            'included_segments' => 'Engaged Users'
        ];
        return $this->sendMessage($message, $parameter);
    }

    /*
     * seçili socketid lerine mesaj atar..
     * */
    public function sendPlayerWithId($message, $player_Ids = [])
    {
        $parameter = [
            'include_player_ids' => $player_Ids
        ];
        return $this->sendMessage($message, $parameter);
    }


    private function sendMessage($message = 'default', array $parameter = [])
    {
        $this->curl->setUrl("https://onesignal.com/api/v1/notifications");
        $this->curl->setHeader([
            'Content-Type: application/json',
            'Authorization: Basic ' . $this->restApiKey
        ]);

        $postFields = [
            'app_id' => $this->appId,
            'contents' => [
                'en' => $message
            ]
        ];

        if (!empty($parameter))
            $postFields = array_merge($postFields, $parameter);

        $postFields = json_encode($postFields);


        return $this->curl->execute($postFields, true);
    }


}