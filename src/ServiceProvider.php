<?php

/*
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\Service\Sale\OAuth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        Auth::extend('sale-oauth', function ($app) {
            return new Guard(
                $app->request,
                new ApiService(config('services.sale-oauth.base_url', ''))
            );
        });
    }
}
