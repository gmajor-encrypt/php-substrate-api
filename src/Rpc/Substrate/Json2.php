<?php

namespace Rpc\Substrate;

class Json2
{

    public static int $lastId = 0;

    /**
     * build substrate call
     *
     * @param string $method
     * @param array $params
     * @return array
     */


    public static function build (string $method, array $params = []): array
    {
        return [
            "id" => self::getLastId(),
            "jsonrpc" => "2.0",
            "method" => $method,
            "params" => $params
        ];
    }

    public static function getLastId (): int
    {
        $lastId = self::$lastId;
        self::$lastId = $lastId + 1;
        return $lastId;
    }
}
