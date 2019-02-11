<?php

namespace VIITech\Helpers\Packagist\DingoAPI;

use Illuminate\Support\ServiceProvider;
use VIITech\Helpers\Constants\EnvVariables;

class CustomDingoSerializerProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){

        // Enable Custom Serializer
        if(env(EnvVariables::API_CUSTOM_SERIALIZER, false)) {
            $this->app[\Dingo\Api\Transformer\Factory::class]->setAdapter(function ($app) {
                $fractal = new \League\Fractal\Manager;
                $fractal->setSerializer(new CustomDingoDataArraySerializer);
                return new \Dingo\Api\Transformer\Adapter\Fractal($fractal);
            });
        }

        // Enable JWT with Dingo API
        if(env(EnvVariables::ENABLE_JWT, false)) {
            /** @var \Dingo\Api\Auth\Auth $dingo_auth */
            $this->app[\Dingo\Api\Auth\Auth::class]->extend('jwt', function ($app) {
                return new \Dingo\Api\Auth\Provider\JWT($app[\Tymon\JWTAuth\JWTAuth::class]);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
