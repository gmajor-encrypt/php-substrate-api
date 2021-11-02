<?php
namespace Rpc;

class SubstrateRpc{

    /**
     * @var IClient
     */
    public IClient $client;


    function  __construct ()
    {
        $conf = config::$config;
        if(!empty($conf["ws_endpoint"])){
            $this->client = new WSClient();
            return;
        }elseif (!empty($conf["http_endpoint"])){
            $this->client = new HttpClient();
            return;
        }
        throw new \InvalidArgumentException("please provider http/ws endpoint");
    }

}
