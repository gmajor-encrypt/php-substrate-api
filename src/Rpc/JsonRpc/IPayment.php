<?php

namespace Rpc\JsonRpc;

interface IPayment
{
    /**
     * Query the detailed fee of a given encoded extrinsic
     *
     * @param string $extrinsic
     * @param string $at
     * @return array
     */
    function queryFeeDetails (string $extrinsic, string $at = ""): array;


    /**
     * Retrieves the fee information for an encoded extrinsic
     *
     * @param string $extrinsic
     * @param string $at
     * @return array
     */
    function queryInfo (string $extrinsic, string $at = ""): array;

}