<?php
/**
 * Created by PhpStorm.
 * User: slot-nuc-2
 * Date: 18.12.2015
 * Time: 13:44
 */

final class CURL
{
    private $ch;
    private static $static;
    private $header = [];
    private $url;

    /*
 * ApiCurl constructor.
 * @param $ch
 */
    private function __construct()
    {
        $this->ch = curl_init();
    }

    private function __clone()
    {
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader(array $header)
    {
        $this->header = $header;
        return $this;
    }


    public static function getInstance()
    {
        if (is_null(self::$static))
            self::$static = new static();

        return self::$static;
    }


    public function execute($query=[], $post = false)
    {
        $query = is_array($query) ? http_build_query($query) : $query;


        $curl_opt =
            [
                CURLOPT_URL => $this->url,
                CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
                CURLOPT_REFERER => $_SERVER['HTTP_HOST'],
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $query,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => 0,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => false,

            ];

        if (!empty($this->header))
            $curl_opt[CURLOPT_HTTPHEADER] = $this->header;


        if (!$post)
            $this->getMethod($curl_opt);



        curl_setopt_array($this->ch, $curl_opt);
        $connectionResponse = curl_exec($this->ch);


        return $connectionResponse;
    }


    function __destruct()
    {
        curl_close($this->ch);
    }


    private function getMethod(&$curlArray)
    {
        unset($curlArray[CURLOPT_POST]);
        unset($curlArray[CURLOPT_POSTFIELDS]);
    }



}


