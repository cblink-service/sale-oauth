<?php

/*
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\Service\Sale\OAuth;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

/**
 * Class ApiService
 * @package Cblink\Service\Sale\OAuth
 */
class ApiService
{
    /**
     * @var string
     */
    protected $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param $authorization
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUser($authorization)
    {
        return $this->request([
            'headers' => [
                'Authorization' => $authorization
            ]
        ]);
    }

    /**
     * @param array $options
     * @param string $method
     * @return false|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(array $options = [], $method = Request::METHOD_POST)
    {
        $options = array_merge([
            'verify' => false,
            'http_errors' => false,
        ], $options);

        $response = $this->getClient()
            ->request(
                $method,
                $this->baseUrl,
                $options
            );

        return $this->response($response);
    }

    /**
     * @param $response
     * @return null|mixed
     */
    public function response($response)
    {
        if ($response->getStatusCode() === 200) {
            $body = json_decode($response->getBody()->getContents(), true);

            if (!json_last_error() && isset($body['err_code'], $body['data']) && $body['err_code'] == 0) {
                return $body['data'];
            }
        }

        return null;
    }


    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        return app(Client::class);
    }
}
