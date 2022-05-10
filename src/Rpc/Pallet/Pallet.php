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
     * For transaction option, it can be set tips or Era
     * default era is immortal, tip is 0
     *
     * @var array
     */
    protected array $options;

    /**
     *
     * @param Rpc $rpc
     * @param string $pallet
     * @param KeyPair $keyPair
     * @param array $opt
     */
    public function __construct (Rpc $rpc, string $pallet, keyPair $keyPair, array $opt = [])
    {
        $this->rpc = $rpc;
        $this->pallet = $pallet;
        $this->keyPair = $keyPair;
        $this->options = $opt;
    }

    /**
     * @param string $call
     * @param array $attributes
     *
     * @return void
     * @throws \InvalidArgumentException|\SodiumException
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
        return $this->rpc->author->submitExtrinsic($signature);
    }


    /**
     * sign Extrinsic
     * support ed25519 or sr25519
     *
     * return signature
     *
     * @param array $call
     * @return string
     * @throws \SodiumException
     */
    public function signAndBuildExtrinsic (array $call): string
    {
        $encodeCall = $this->rpc->codec->createTypeByTypeString("Call")->setMetadata($this->rpc->metadata)->encode($call);
        $genesisHash = $this->rpc->chain->getBlockHash(0); // chain_getBlockHash

        // build ExtrinsicOption
        $opt = new ExtrinsicOption($genesisHash);
        $opt->era = $this->options["era"]; // Era  MortalEra
        $opt->nonce = $this->rpc->system->accountNextIndex(ss58::encode($this->keyPair->pk, 42)); // nonce system_accountNextIndex
        $runtimeVersion = $this->rpc->state->getRuntimeVersion();
        $opt->specVersion = $runtimeVersion["specVersion"]; // spec version state_getRuntimeVersion
        $opt->tip = $this->options["tip"]; //
        $opt->transactionVersion = $runtimeVersion["transactionVersion"]; // TransactionVersion

        // sign ExtrinsicPayload
        $payload = new ExtrinsicPayload($opt, $encodeCall);
        $signature = $payload->sign($this->keyPair, $payload->encode($this->rpc->codec));

        // extrinsic build
        $extrinsic = [
            'version' => '84',
            "account_id" => ["Id" => $this->keyPair->pk],
            "signature" => [$this->keyPair->type => $signature],
            "era" => $opt->era,
            "nonce" => $opt->nonce,
            "tip" => $opt->tip,
            'module_id' => $call["module_id"],
            'call_name' => $call["call_name"],
            'params' => $call["params"]
        ];
        // extrinsic encode
        return $this->rpc->codec->createTypeByTypeString("Extrinsic")->setMetadata($this->rpc->metadata)->encode($extrinsic);
    }
}

