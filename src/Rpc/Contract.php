<?php

namespace Rpc;

use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use Codec\Utils;
use InvalidArgumentException;
use Rpc\Contract\Abi\ContractMetadataV4;
use SodiumException;

class Contract
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
    protected ScaleInstance $codec;

    /**
     * Tx send transaction instance
     *
     * @param Rpc $rpc
     */

    protected Tx $tx;


    /**
     * abi contract metadata abi
     *
     * @var ContractMetadataV4
     */
    protected ContractMetadataV4 $ABI;

    /**
     * contract address
     *
     * @var string
     */
    protected string $address;

    /**
     * Contract construct
     *
     *
     * @param Tx $tx
     * @param string $address
     * @param ContractMetadataV4|null $ABI
     */
    public function __construct (Tx $tx, string $address = "", ContractMetadataV4 $ABI = null)
    {
        $this->codec = $tx->codec;
        $this->metadata = $tx->metadata;
        $this->tx = $tx;
        $this->ABI = $ABI;
        $this->address = $address;
    }


    /**
     * Set Abi prop
     *
     * @param ContractMetadataV4 $ABI
     * @return void
     */
    public function SetABI (ContractMetadataV4 $ABI)
    {
        $this->ABI = $ABI;
    }

    /**
     * Set contract address
     *
     * @param string $address
     * @return void
     */
    public function SetContract (string $address)
    {
        $this->address = $address;
    }


    /**
     * deploy new contract
     *
     * @param string $code
     * @param string $data
     * @param array $option set gasLimit storageDepositLimit
     * @return string
     */

    // https://github.com/paritytech/substrate/blob/0ce39208841e519920b57d3ba5a3962188c4c66c/frame/contracts/src/lib.rs#L187
    public function new (string $code, string $data, array $option = []): string
    {
        $code = Utils::trimHex($code);
        $data = Utils::trimHex($data);
        $gasLimit = array_key_exists("gasLimit", $option) ? $option["gasLimit"] : "50000000000";
        $storageDepositLimit = array_key_exists("storageDepositLimit", $option) ? $option["storageDepositLimit"] : 0;
        $salt = Utils::hexToBytes(dechex(time()));
        // Contracts.Instantiate_with_code(value,gas_limit,storage_deposit_limit,code,data,salt)
        return $this->tx->Contracts->instantiate_with_code($storageDepositLimit, ["proof_size" => 0, "ref_time" => $gasLimit], null, Utils::hexToBytes($code), Utils::hexToBytes($data), $salt);
    }

    /**
     * magic function __call
     * it can be query contact storage or exec method
     *
     * attributes is contract function args
     * If you want to set storageDepositLimit and gasLimit or value, you can put them in the last parameters,
     * for example：
     * with option
     * queryStorage("param1","param2",["gasLimit"=>500000,"storageDepositLimit"=>0,"value"=>0])
     *
     * none option:
     * queryStorage("param1","param2",[])
     *
     *
     * @param string $call
     * @param array $attributes
     *
     *
     *
     * @return void
     * @throws InvalidArgumentException|SodiumException
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
        $gasLimit = array_key_exists("gasLimit", $option) ? $option["gasLimit"] : 0;
        $storageDepositLimit = array_key_exists("storageDepositLimit", $option) ? $option["storageDepositLimit"] : null;
        $value = array_key_exists("value", $option) ? $option["value"] : 0;

        array_pop($attributes);
        if (count($attributes) != count($message["args"])) {
            throw new InvalidArgumentException(sprintf("invalid param, expect %d, actually %d", count($message["args"]), count($attributes)));
        }

        $data = Utils::trimHex($this->tx->getKeyPairPk()); // signer
        $data = $data . Utils::trimHex($this->address);      // contract address
        $data = $data . $this->codec->createTypeByTypeString("Balance")->encode($value);
        $data = $data . $this->codec->createTypeByTypeString("compact<u64>")->encode($gasLimit);
        $data = $data . $this->codec->createTypeByTypeString("option<compact<U128>>")->encode($storageDepositLimit);
        $data = $data . $this->codec->createTypeByTypeString("bytes")->encode(Util::trimHex($message["selector"]));

        foreach ($message["args"] as $index => $arg) {
            $data = $data . $this->codec->createTypeByTypeString($this->ABI->getTypeNameBySiType($arg["type"]))->encode($attributes[$index]);
        }
        $rawValue = $this->tx->rpc->state->call("ContractsApi_call", $data);
        $result = $this->codec->process("ContractExecResult", new ScaleBytes($rawValue));
        if (array_key_exists("Ok", $result["result"])) {
            return $this->codec->process($this->ABI->getTypeNameBySiType($message["returnType"]["type"]), new ScaleBytes($result["result"]["Ok"]["data"]));
        }
        return null;
    }
}
