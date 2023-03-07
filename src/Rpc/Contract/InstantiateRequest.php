<?php

namespace Rpc\Contract;
class InstantiateRequest
{

    protected string $contractAddress;

    protected array|string $result;

    /**
     *
     * @param string $contractAddress
     * @param array|string $result
     */
    public function __construct (string $contractAddress, array|string $result)
    {
        $this->contractAddress = $contractAddress;
        $this->result = $result;
    }

    /**
     * get prop $contractAddress
     *
     * @return string
     */
    public function getContractAddress (): string
    {
        return $this->contractAddress;
    }

    /**
     * get prop $result
     *
     * @return array|string
     */
    public function getResult (): array|string
    {
        return $this->result;
    }

    /**
     * get deploy contract transaction hash
     * @return string
     */
    public function getTransactionHash (): string
    {
        if (is_array($this->result)) {
            throw new \InvalidArgumentException("result is InstantiateRequest array");
        }
        return $this->result;
    }



    /**
     * get deploy contract block hash
     * @return string
     */
    public function getInBlockHash (): string
    {
        if (!is_array($this->result)) {
            throw new \InvalidArgumentException("result is transaction hash");
        }
        return $this->result["params"]["result"]["inBlock"];
    }
}
