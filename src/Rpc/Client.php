<?php

namespace Rpc;

class Client implements IClient
{
    /**
     * @var string
     */
    public static string $HTTP_ENDPOINT = "";

    /**
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

    /**
     * current timestamp
     *
     * @return string
     */
    public static function getTimestamp (): string
    {
        ini_set("date.timezone", "UTC");
        return date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 4) . 'Z';
    }

    public function read (string $method, array $params = []): mixed
    {
        // TODO: Implement subscribe() method.
    }

    public function close ()
    {
    }
}
