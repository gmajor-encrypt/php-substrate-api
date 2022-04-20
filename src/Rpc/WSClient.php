<?php

namespace Rpc;

use WebSocket\BadOpcodeException;
use WebSocket\Client as WS;
use Rpc\Substrate\Json2;
use WebSocket\ConnectionException;

class WSClient extends Client
{
    /**
     * websocket connection instance
     *
     * @var WS
     */
    public WS $client;

    /**
     * Custom http header
     *
     * @var array
     */
    public array $header;

    /**
     * websocket connection status
     *
     * @var bool
     */
    public bool $isConnected;

    /**
     * Websocket constructor.
     *
     * @param string $endpoint
     * @param array $header custom http header
     */
    public function __construct (string $endpoint, array $header = [])
    {
        if (!str_starts_with($endpoint, 'ws://') && !str_starts_with($endpoint, 'wss://')) {
            throw new \InvalidArgumentException(sprintf("invalid protocol %s, only support ws or wss protocol", $endpoint));
        }

        $this->client = new WS($endpoint, ["timeout" => 60, "fragment_size" => 1024 * 100, "headers" => $header]);
        $this->header = $header;
        $this->isConnected = true;
        parent::__construct($endpoint);
    }

    /**
     * subscribe from websocket
     *
     * @param string $method
     * @param array $params
     * @return array
     * @throws BadOpcodeException|ConnectionException
     */
    public function read (string $method, array $params = []): array
    {
        if (!$this->isConnected) {
            throw new ConnectionException("this connection has been closed");
        }
        $this->client->send(json_encode(Json2::build($method, $params)));
        return json_decode($this->client->receive(), true);
    }

    /**
     * close websocket connect
     */
    public function close ()
    {
        $this->isConnected = false;
        $this->client->close();
    }
}
