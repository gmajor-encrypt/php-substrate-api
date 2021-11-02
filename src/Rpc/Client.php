<?php

namespace Rpc;

class Client implements IClient
{
    /**
     * @var string
     */
    static string $HTTP_ENDPOINT = "";

    /**
     * @var string
     */
    static string $WS_ENDPOINT = "";

    function __construct ()
    {
        self::setParams(config::$config);
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
    public static function getTimestamp (): string
    {
        ini_set("date.timezone", "UTC");
        return date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 4) . 'Z';
    }
}
