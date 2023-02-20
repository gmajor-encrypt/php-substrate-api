<?php

namespace Rpc\Contract;

use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use Codec\Utils;
use InvalidArgumentException;
use Rpc\Contract\Abi\ContractMetadataV4;
use Rpc\Rpc;
use Rpc\Tx;
use Rpc\Util;

/**
 * State instance
 * for query contract storage state
 */
class State
{

    /**
     * runtime metadata, init after Rpc instance init
     *
     * @var array
     */
    protected array $metadata;

    /**
     * scale code instance
     *
     * @var ScaleInstance
     */
    public ScaleInstance $codec;

    /**
     * Tx send transaction instance
     *
     * @param Rpc $rpc
     */

    public Tx $tx;


    /**
     * abi contract metadata abi
     *
     * @var ContractMetadataV4
     */
    public ContractMetadataV4 $ABI;

    /**
     * contract address
     *
     * @var string
     */
    public string $address;


    public function __construct (Tx $tx, string $address = "", ContractMetadataV4 $ABI = null)
    {
        $this->codec = $tx->codec;
        $this->metadata = $tx->metadata;
        $this->tx = $tx;
        $this->ABI = $ABI;
        $this->address = $address;
    }


    /**
     * magic function __call
     * it can be query contact storage or exec method
     *
     * attributes is contract function args
     * queryStorage("param1","param2")
     *
     *
     * @param string $call
     * @param array $attributes
     *
     * @return object
     * @throws InvalidArgumentException
     */
    public function __call (string $call, array $attributes)
    {
        if ($this->address == "" or $this->ABI->is_empty()) {
            throw new InvalidArgumentException("contract address or abi not set");
        }
        $message = $this->ABI->message($call);
        if (count($message) == 0) {
            throw new InvalidArgumentException(sprintf("unknown method %s", $call));
        }

        if (count($attributes) != count($message["args"])) {
            throw new InvalidArgumentException(sprintf("invalid param, expect %d, actually %d", count($message["args"]), count($attributes)));
        }
        // , $this->ABI->getTypeNameBySiType($message["returnType"]["type"])];
        return (object)["result" => $this->getState($message, $attributes), "type" => $this->ABI->getTypeNameBySiType($message["returnType"]["type"])];
    }


    /**
     * @param array $message
     * @param array $attributes
     * @return mixed
     */
    public function getState (array $message, array $attributes): mixed
    {
        $codec = $this->codec;
        $data = Utils::trimHex($this->tx->getKeyPairPk()); // signer
        $data = $data . Utils::trimHex($this->address);      // contract address
        $data = $data . $codec->createTypeByTypeString("Balance")->encode(0);
        $data = $data . $codec->createTypeByTypeString("compact<u64>")->encode(0);
        $data = $data . $codec->createTypeByTypeString("option<compact<U128>>")->encode(null);
        $data = $data . $codec->createTypeByTypeString("bytes")->encode(Util::trimHex($message["selector"]));

        foreach ($message["args"] as $index => $arg) {
            $data = $data . $codec->createTypeByTypeString($this->ABI->getTypeNameBySiType($arg["type"]))->encode($attributes[$index]);
        }
        $rawValue = $this->tx->rpc->state->call("ContractsApi_call", $data);
        return $codec->process("ContractExecResult", new ScaleBytes($rawValue));
    }

}
