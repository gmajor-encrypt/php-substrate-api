<?php

namespace Rpc;

interface IClient
{
    /**
     * read interface
     * Read/subscribe HTTP/Websocket to RPC endpoints
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function read(string $method, array $params = []): mixed;


    /**
     * close interface
     */
    public function close();
}
