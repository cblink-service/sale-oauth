<?php

/*
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\Service\Sale\OAuth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Http\Request;

/**
 * Class Guard
 * @package Cblink\Service\OAuth
 */
class Guard implements AuthGuard
{
    use GuardHelpers;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ApiService
     */
    protected $service;

    /**
     * @var User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    public function __construct(Request $request, ApiService $service)
    {
        $this->request = $request;
        $this->service = $service;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        if ($response = $this->validate()) {
            $user = new User($response);
        }

        return $this->user = $user;
    }

    /**
     * @param array $credentials
     * @return bool|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function validate(array $credentials = [])
    {
        $token = $this->request->header('Authorization');

        $user = $this->service->getUser($token);

        return $user;
    }
}
