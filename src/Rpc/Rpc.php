<?php

namespace Rpc;

use Codec\Base;
use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use Rpc\JsonRpc\Author;
use Rpc\JsonRpc\Babe;
use Rpc\JsonRpc\ChildState;
use Rpc\JsonRpc\Contracts;
use Rpc\JsonRpc\Dev;
use Rpc\JsonRpc\Engine;
use Rpc\JsonRpc\Grandp;
use Rpc\JsonRpc\IAuthor;
use Rpc\JsonRpc\IBabe;
use Rpc\JsonRpc\IChain;
use Rpc\JsonRpc\IChildState;
use Rpc\JsonRpc\IContracts;
use Rpc\JsonRpc\IDev;
use Rpc\JsonRpc\IEngine;
use Rpc\JsonRpc\IGrandpa;
use Rpc\JsonRpc\IMmr;
use Rpc\JsonRpc\IOffchain;
use Rpc\JsonRpc\IPayment;
use Rpc\JsonRpc\IRpc;
use Rpc\JsonRpc\IState;
use Rpc\JsonRpc\ISyncState;
use Rpc\JsonRpc\ISystem;
use Rpc\JsonRpc\Chain;
use Rpc\JsonRpc\Mmr;
use Rpc\JsonRpc\Offchain;
use Rpc\JsonRpc\Payment;
use Rpc\JsonRpc\State;
use Rpc\JsonRpc\SyncState;
use Rpc\JsonRpc\System;
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
     * json Rpc instance start
     */

    public IAuthor $author;
    public IBabe $babe;
    public IChain $chain;
    public IChildState $childState;
    public ISystem $system;
    public ISyncState $syncState;
    public IState $state;
    public IRpc $rpc;
    public IMmr $mmr;
    public IPayment $payment;
    public IOffchain $offchain;
    public IEngine $engine;
    public IDev $dev;
    public IContracts $contracts;
    public IGrandpa $grandpa;
    /**
     * json Rpc instance end
     */


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

        // json rpc
        $this->author = new Author($this->client);
        $this->chain = new Chain($this->client);
        $this->babe = new Babe($this->client);
        $this->childState = new ChildState($this->client);
        $this->contracts = new Contracts($this->client);
        $this->dev = new Dev($this->client);
        $this->engine = new Engine($this->client);
        $this->grandpa = new Grandp($this->client);
        $this->mmr = new Mmr($this->client);
        $this->offchain = new Offchain($this->client);
        $this->payment = new Payment($this->client);
        $this->rpc = new JsonRpc\Rpc($this->client);
        $this->state = new State($this->client);
        $this->syncState = new SyncState($this->client);
        $this->system = new System($this->client);
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
