<?php

namespace Rpc\Substrate;

class Json2
{
    /**
     * build substrate call
     *
     * @param string $method
     * @param array|string $params
     * @return array
     */
    public static function build (string $method, $params = [])
    {
        $struct = [
            "id" => rand(0, 10000),
            "jsonrpc" => "2.0",
            "method" => $method,
            "params" => $params
        ];
        return $struct;
    }


}