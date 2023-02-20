<?php

namespace Rpc\Contract;

use Codec\Types\ScaleInstance;
use Codec\Utils;
use Rpc\Rpc;
use Rpc\Tx;
use InvalidArgumentException;
use Rpc\Contract\Abi\ContractMetadataV4;
use Rpc\Util;

/**
 * Call instance
 * for call contract exec method
 */
class Call
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
     * If you want to set storageDepositLimit and gasLimit or value, you can put them in the last parameters,
     * for example：
     * with option
     * queryStorage("param1","param2",["gasLimit"=>["proof_size" => $proof_size, "ref_time" => $ref_time],"storageDepositLimit"=>0,"value"=>0])
     *
     * none option:
     * queryStorage("param1","param2",[])
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

        $option = end($attributes);
        array_pop($attributes);
        $state = new State($this->tx, $this->address, $this->ABI);
        $defaultGas = ContractExecResult::deserialization($state->getState($message, $attributes));

        $gasLimit = array_key_exists("gasLimit", $option) ? $option["gasLimit"] : $defaultGas->gasRequired;
        $storageDepositLimit = array_key_exists("storageDepositLimit", $option) ? $option["storageDepositLimit"] : null;
        $value = array_key_exists("value", $option) ? $option["value"] : 0;

        if (count($attributes) != count($message["args"])) {
            throw new InvalidArgumentException(sprintf("invalid param, expect %d, actually %d", count($message["args"]), count($attributes)));
        }

        $codec = $this->codec;
        $data = Util::trimHex($message["selector"]);
        foreach ($message["args"] as $index => $arg) {
            $data = $data . $codec->createTypeByTypeString($this->ABI->getTypeNameBySiType($arg["type"]))->encode($attributes[$index]);
        }
        $gasLimit = ContractExecResult::convertGasRequired($gasLimit);
        if (count($gasLimit) == 0) {
            throw new InvalidArgumentException("invalid gas_limit option");
        }

        // contracts.call("dest","value","gasLimit","storageDepositLimit","data")
        return $this->tx->Contracts->call(
            ["Id" => $this->address],
            $value,
            $gasLimit,
            $storageDepositLimit,
            Utils::hexToBytes($data)
        );
    }
}