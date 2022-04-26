<?php

namespace Rpc\Pallet;

use Rpc\KeyPair\KeyPair;
use Rpc\Rpc;
use Rpc\ss58;

class Pallet
{

    /**
     * rpc client inject
     *
     * @var Rpc
     */
    public Rpc $rpc;

    /**
     * pallet name;
     *
     * @var string
     */
    public string $pallet;


    /**
     * tx signer KeyPair
     *
     * @var KeyPair
     */
    private KeyPair $keyPair;

    /**
     *
     * @param Rpc $rpc
     * @param string $pallet
     * @param KeyPair $keyPair
     */
    public function __construct (Rpc $rpc, string $pallet, keyPair $keyPair)
    {
        $this->rpc = $rpc;
        $this->pallet = $pallet;
        $this->keyPair = $keyPair;
    }

    /**
     * @param string $call
     * @param array $attributes
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __call (string $call, array $attributes)
    {
        $signature = $this->signAndBuildExtrinsic(["module_id" => $this->pallet, "call_name" => $call, "params" => $attributes]);
        return $this->submitAndWatchExtrinsic($signature);
    }


    /**
     * submit and send submitAndWatchExtrinsic rpc
     * submitAndWatchExtrinsic
     * send signed Extrinsic
     *
     * @param string $signature
     * @return mixed
     */
    public function submitAndWatchExtrinsic (string $signature): mixed
    {
        return $this->rpc->author->submitAndWatchExtrinsic($signature);
    }


    /**
     * sign Extrinsic
     * support ed25519 or sr25519
     *
     * return signature
     *
     * @param array $call
     * @return string
     */
    public function signAndBuildExtrinsic (array $call): string
    {
        $encodeCall = $this->rpc->codec->createTypeByTypeString("Call")->setMetadata($this->rpc->metadata)->encode($call);
        $genesisHash = $this->rpc->chain->getBlockHash("0x0"); // chain_getBlockHash
        $opt = new ExtrinsicOption($genesisHash);
        $opt->era = "00"; // Era  MortalEra
        $opt->nonce = $this->rpc->system->accountNextIndex(ss58::encode($this->keyPair->pk, 42)); // nonce system_accountNextIndex
        $runtimeVersion = $this->rpc->state->getRuntimeVersion();
        $opt->specVersion = $runtimeVersion["specVersion"]; // spec version state_getRuntimeVersion
        $opt->tip = "0"; //
        $opt->transactionVersion = $runtimeVersion["transactionVersion"]; // TransactionVersion

        $payload = new ExtrinsicPayload($opt, $encodeCall);
        $signature = $payload->sign($this->keyPair, $payload->encode($this->rpc->codec));

        $extrinsic = [
            "extrinsic_length" => 145,
            'version' => '84',
            "account_id" => ["Id" => $this->keyPair->pk],
            "signature" => [$this->keyPair->type => $signature],
            "era" => $opt->era,
            "nonce" => $opt->nonce,
            "tip" => $opt->nonce,
            'module_id' => $call["module_id"],
            'call_name' => $call["call_name"],
            'params' => $call["params"]
        ];
        return $this->rpc->codec->createTypeByTypeString("Extrinsic")->setMetadata($this->rpc->metadata)->encode($extrinsic);
    }
}
