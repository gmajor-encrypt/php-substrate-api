<?php

namespace Rpc;

class Client implements IClient
{
    /**
     * http endpoint url
     * @var string
     */
    public static string $HTTP_ENDPOINT = "";

    /**
     * websocket endpoint url
     * @var string
     */
    public static string $WS_ENDPOINT = "";


    public function __construct (string $endpoint)
    {

        $parse = parse_url($endpoint);
        if (!array_key_exists("scheme", $parse)) {
            throw new \InvalidArgumentException("endpoint only support http or ws");
        }
        if ($parse["scheme"] == "http" || $parse["scheme"] == "https") {
            self::$HTTP_ENDPOINT = $endpoint;
            return;
        }
        if ($parse["scheme"] == "ws" || $parse["scheme"] == "wss") {
            self::$WS_ENDPOINT = $endpoint;
            return;
        }
        throw new \InvalidArgumentException("endpoint only support http or ws");
    }


    public function read (string $method, array $params = []): mixed
    {
        // TODO: Implement subscribe() method.
    }

    public function close ()
    {
    }
}
