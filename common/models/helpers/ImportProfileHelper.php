<?php

namespace common\models\helpers;

use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\Exception;

/**
 * Class ImportProfileHelper
 * @package common\models\helpers
 */
class ImportProfileHelper extends ModelHelper
{
    /**
     * @param $url
     * @param $token
     *
     * @return string
     * @throws Exception
     */
    public static function sendRestRequest($url, $token, $params = [])
    {
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $data = [
            'access-token' => $token,
        ];

        foreach ($params as $name => $param) {
            $data[$name] = $param;
        }

        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl($url)
            ->setData($data)
            ->setHeaders([
                'Accept' => 'application/json',
            ])->send();

        if ($response->isOk) {
            return $response->content;
        } else {
            throw new Exception('Invalid remote server response');
        }
    }

    /**
     * @param $url
     * @param $token
     *
     * @return array
     */
    public static function getRemoteSections($url, $token)
    {
        $array = json_decode(self::sendRestRequest($url, $token));

        return ArrayHelper::toArray($array);
    }
}