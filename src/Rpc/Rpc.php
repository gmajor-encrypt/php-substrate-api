<?php

namespace Rpc;

use Codec\Base;
use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use Rpc\Substrate\Method;
use WebSocket\ConnectionException;

class Rpc
{

    /**
     * @var IClient $client
     */
    public IClient $client;

    /**
     * @var array $methods
     */
    public array $methods;


    /**
     * runtime metadata, init after Rpc instance init
     *
     * @var array
     */
    public array $metadata;

    /**
     * scale code instance
     *
     * @var ScaleInstance
     */
    public ScaleInstance $codec;

    /**
     * Rpc client, allow websocket(wss/ws) or http(https/http)
     *
     * @param string $endpoint
     * @param array $header
     * @throws ConnectionException
     */
    public function __construct (string $endpoint, array $header = [])
    {
        $this->client = SubstrateRpc::setClient($endpoint, $header);
        $m = new Method($this->client);
        $this->methods = $m->methods()["result"]["methods"];
        $metadataRaw = $m->getMetadata();
        if (empty($metadataRaw)) {
            throw new ConnectionException("state_getMetadata get error, please retry");
        }
        $this->codec = new ScaleInstance(Base::create());
        $this->metadata = $this->codec->process("metadata", new ScaleBytes($metadataRaw))["metadata"];
    }


    /**
     * @param string $pallet
     * @return Method
     */
    public function __get (string $pallet): Method
    {
        return new Method($this->client, $pallet);
    }
}
