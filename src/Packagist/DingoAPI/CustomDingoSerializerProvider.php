<?php

namespace VIITech\Helpers\Packagist\DingoAPI;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Auth\Provider\JWT;
use Dingo\Api\Transformer\Adapter\Fractal;
use Dingo\Api\Transformer\Factory;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use Tymon\JWTAuth\JWTAuth;
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
            $this->app[Factory::class]->setAdapter(function ($app) {
                $fractal = new Manager;
                $fractal->setSerializer(new CustomDingoDataArraySerializer);
                return new Fractal($fractal);
            });
        }

        // Enable JWT with Dingo API
        if(env(EnvVariables::ENABLE_JWT, false)) {
            /** @var Auth $dingo_auth */
            $this->app[Auth::class]->extend('jwt', function ($app) {
                return new JWT($app[JWTAuth::class]);
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
