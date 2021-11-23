<?php

namespace Rpc;

class Util
{

    /**
     * HTTP REQUEST POST json payload
     *
     * @param $endPoint
     * @param $params
     * @return mixed
     */
    public static function requestWithPayload ($endPoint, $params): array
    {
        $body = $params ? json_encode($params, JSON_UNESCAPED_SLASHES) : '';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_URL, $endPoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $return = curl_exec($ch);

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerTotal = strlen($return);
        $bodySize = $headerTotal - $headerSize;
        $body = substr($return, $headerSize, $bodySize);
        return json_decode($body, true);
    }

    /**
     * @param $hexString string
     * @return string
     */
    public static function trimHex (string $hexString): string
    {
        return preg_replace('/0x/', '', $hexString);
    }

    /**
     * @param $hexString string
     * @return string
     */
    public static function addHex (string $hexString): string
    {
        return str_starts_with($hexString, '0x') ? $hexString : '0x' . $hexString;
    }


}
