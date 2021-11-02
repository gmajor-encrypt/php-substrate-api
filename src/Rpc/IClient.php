<?php
namespace Rpc;

interface IClient{
    /**
     * subscribe interface
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function subscribe(string $method, array $params = []):mixed;
}
