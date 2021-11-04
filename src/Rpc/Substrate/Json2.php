<?php

namespace Rpc\Substrate;

class Json2
{
    /**
     * build substrate call
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public static function build(string $method, array $params = []):array
    {
        return [
            "id" => rand(0, 10000),
            "jsonrpc" => "2.0",
            "method" => $method,
            "params" => $params
        ];
    }
}
