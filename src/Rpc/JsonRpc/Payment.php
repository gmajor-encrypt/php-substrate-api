<?php

namespace Rpc\JsonRpc;

class Payment extends Base implements IPayment
{

    /**
     * Query the detailed fee of a given encoded extrinsic
     *
     * @param string $extrinsic
     * @param string $at
     * @return array
     */
    function queryFeeDetails (string $extrinsic, string $at = ""): array
    {
        $res = $this->client->read("payment_queryFeeDetails", [$extrinsic, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the fee information for an encoded extrinsic
     *
     * @param string $extrinsic
     * @param string $at
     * @return array
     */
    function queryInfo (string $extrinsic, string $at = ""): array
    {
        $res = $this->client->read("payment_queryInfo", [$extrinsic, $at]);
        return $res["result"];
    }
}