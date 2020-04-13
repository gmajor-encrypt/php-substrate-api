<?php

namespace Rpc;

class Config
{
    public static $config = [
        "http_endpoint" => "http://127.0.0.1:9933",
        "ws_endpoint" => "ws://127.0.0.1:9944",
    ];


    /**
     * setHttpEndPoint
     * @param string $endpoint
     */
    public static function setHttpEndPoint(string $endpoint)
    {
        self::$config["http_endpoint"] = $endpoint;
    }


    /**
     * setWSEndPoint
     * @param string $endpoint
     */
    public static function setWSEndPoint(string $endpoint)
    {
        self::$config["ws_endpoint"] = $endpoint;
    }

}