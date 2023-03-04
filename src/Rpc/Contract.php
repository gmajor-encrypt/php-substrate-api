<?php

namespace Rpc;

use Codec\Base;
use Codec\ScaleBytes;
use Codec\Utils;
use Rpc\Contract\Abi\ContractMetadataV4;
use Rpc\Contract\Call;
use Rpc\Contract\ContractExecResult;
use Rpc\Contract\State;

class Contract
{


    /**
     * Tx send transaction instance
     *
     * @param Rpc $rpc
     */

    protected Tx $tx;


    /**
     * state instance
     *
     * @var State
     */
    public State $state;

    /**
     * call instance
     *
     * @var Call $call
     */
    public Call $call;


    /**
     * weight v2 reg
     *
     * @var array|string[]
     */
    protected array $defaultReg = [
        "weight" => "weightV2"
    ];


    /**
     * abi contract metadata abi
     *
     * @var ContractMetadataV4
     */
    public ContractMetadataV4 $ABI;

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
        $this->tx = $tx;
        $Generator = $tx->codec->getGenerator();
        Base::regCustom($Generator, $this->defaultReg);
        $this->ABI = new ContractMetadataV4();
        if (!is_null($ABI)) {
            $this->ABI = $ABI;
            $this->state = new State($tx, $address, $ABI);
            $this->call = new Call($tx, $address, $ABI);
        }
    }


    /**
     * deploy new contract
     *
     * @param string $code contract wasm code
     * @param string|array $data constructor input data or constructor args if abi set
     * @param array $option set gasLimit storageDepositLimit(optional)
     * @return string
     */

    // https://github.com/paritytech/substrate/blob/0ce39208841e519920b57d3ba5a3962188c4c66c/frame/contracts/src/lib.rs#L187
    public function new (string $code, mixed $data, array $option = []): string
    {
        $code = Utils::trimHex($code);
        if ($this->ABI->is_empty()) {
            if (!is_string($data)) {
                throw new \InvalidArgumentException("Invalid constructors input");
            }
        } else {
            if (!is_array($data)) {
                throw new \InvalidArgumentException("Invalid constructors args, args is array");
            }
            $args = $data;
            $constructors = $this->ABI->constructor();
            if (count($constructors["args"]) != count($args)) {
                throw new \InvalidArgumentException(sprintf("invalid param, expect %d, actually %d", count($constructors["args"]), count($args)));
            }
            $data = $constructors["selector"];
            foreach ($constructors["args"] as $index => $arg) {
                $data = $data . $this->tx->codec->createTypeByTypeString($this->ABI->getTypeNameBySiType($arg["type"]["type"]))->encode($args[$index]);
            }
        }
        $data = Utils::trimHex($data);
        $salt = dechex(time());

        // estimate gasLimit
        $defaultGas = ContractExecResult::deserialization($this->instantiateRequest($code, $data, $salt));
        $gasLimit = array_key_exists("gasLimit", $option) ? ["proof_size" => 0, "ref_time" => $option["gasLimit"]] : ContractExecResult::convertGasRequired($defaultGas->gasRequired);
        $storageDepositLimit = array_key_exists("storageDepositLimit", $option) ? $option["storageDepositLimit"] : 0;

        // Contracts.Instantiate_with_code(value,gas_limit,storage_deposit_limit,code,data,salt)
        return $this->tx->Contracts->instantiate_with_code($storageDepositLimit, $gasLimit, null, Utils::hexToBytes($code), Utils::hexToBytes($data), Utils::hexToBytes($salt));
    }


    /**
     * estimate gasLimit
     * return InstantiateRequest struct
     *
     * @param string $code
     * @param string $input
     * @param string $salt
     * @return mixed
     */
    public function instantiateRequest (string $code, string $input, string $salt): mixed
    {
        $codec = $this->tx->codec;
        $data = Utils::trimHex($this->tx->getKeyPairPk()); // signer
        $data = $data . $codec->createTypeByTypeString("Balance")->encode(0);
        $data = $data . $codec->createTypeByTypeString("Option<u64>")->encode(null); // gas_limit
        $data = $data . $codec->createTypeByTypeString("Option<u64>")->encode(null); // proof_size
        $data = $data . $codec->createTypeByTypeString("Option<Balance>")->encode(null); // storage_deposit_limit
        $data = $data . $codec->createTypeByTypeString("bytes")->encode($code); // bytes
        $data = $data . $codec->createTypeByTypeString("bytes")->encode($input); // bytes
        $data = $data . $codec->createTypeByTypeString("bytes")->encode($salt); // salt
        $rawValue = $this->tx->rpc->state->call("ContractsApi_instantiate", $data);
        return $codec->process("ContractInstantiateResult", new ScaleBytes($rawValue));
    }
}
