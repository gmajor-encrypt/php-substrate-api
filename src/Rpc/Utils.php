<?php

namespace Rpc;

class Utils
{
    /**
     * @var string
     */
    static $HTTP_ENDPOINT = "";

    /**
     * @var string
     */
    static $WS_ENDPOINT = "";

    function __construct ()
    {
        self::setParams(config::$config);
    }

    /**
     * HTTP REQUEST
     *
     * @param $requestPath
     * @param $params
     * @param $method
     * @return bool|false|mixed|string
     */
    public static function request ($requestPath, $params, $method)
    {

        if (strtoupper($method) == 'GET') {
            $requestPath .= $params ? '?' . http_build_query($params) : '';
            $params = [];

        }
        $body = $params ? json_encode($params, JSON_UNESCAPED_SLASHES) : '';
        $url = self::$HTTP_ENDPOINT . $requestPath;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);


        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        // 头信息
        curl_setopt($ch, CURLOPT_HEADER, true);

        $return = curl_exec($ch);

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerTotal = strlen($return);
        $bodySize = $headerTotal - $headerSize;
        $body = substr($return, $headerSize, $bodySize);
        $body = json_decode($body, true);
        return $body;
    }


    /**
     * @param $configs
     */
    public static function setParams ($configs)
    {
        self::$HTTP_ENDPOINT = $configs["http_endpoint"];
        self::$WS_ENDPOINT = $configs["ws_endpoint"];
    }

    /**
     * @return string
     */
    public static function getTimestamp ()
    {
        ini_set("date.timezone", "UTC");
        return date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 4) . 'Z';
    }
}
