<?php

namespace Rpc\JsonRpc;

class Contracts extends Base implements IContracts
{

    /**
     * Executes a call to a contract
     *
     * @param string $callRequest
     * @param string $at
     * @return array
     */
    function call (string $callRequest, string $at = ""): array
    {
        $res = $this->client->read("contracts_call", [$callRequest, $at]);
        return $res["result"];
    }

    /**
     * Returns the value under a specified storage key in a contract
     *
     * @param string $address
     * @param string $key
     * @param string $at
     * @return string
     */
    function getStorage (string $address, string $key, string $at = ""): string
    {
        $res = $this->client->read("contracts_getStorage", [$address, $key, $at]);
        return $res["result"];
    }

    /**
     * Instantiate a new contract
     *
     * @param string $request
     * @param string $at
     * @return array
     */
    function instantiate (string $request, string $at = ""): array
    {
        $res = $this->client->read("contracts_instantiate", [$request, $at]);
        return $res["result"];
    }

    /**
     * Returns the projected time a given contract will be able to sustain paying its rent
     *
     * @param string $address
     * @param string $at
     * @return int
     */
    function rentProjection (string $address, string $at = ""): int
    {
        $res = $this->client->read("contracts_rentProjection", [$address, $at]);
        return $res["result"];
    }

    /**
     * Upload new code without instantiating a contract from it
     *
     * @param string $uploadRequest
     * @param string $at
     * @return array
     */
    function uploadCode (string $uploadRequest, string $at = ""): array
    {
        $res = $this->client->read("contracts_upload_code", [$uploadRequest, $at]);
        return $res["result"];
    }
}