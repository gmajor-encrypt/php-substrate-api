<?php

namespace Rpc;

class Config
{
    /**
     * @var array|string[]
     */
    public static array $config = [
        "http_endpoint" => "",
        "ws_endpoint" => "",
    ];


    public static function setEndPoint(string $endpoint)
    {
        $parse = parse_url($endpoint);
        if (!array_key_exists("scheme", $parse)) {
            throw new \InvalidArgumentException("endpoint only support http or ws");
        }
        if ($parse["scheme"] == "http" || $parse["scheme"] == "https") {
            self::$config["http_endpoint"] = $endpoint;
            return;
        }
        if ($parse["scheme"] == "ws" || $parse["scheme"] == "wss") {
            self::$config["ws_endpoint"] = $endpoint;
            return;
        }
        throw new \InvalidArgumentException("endpoint only support http or ws");
    }
}
