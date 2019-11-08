<?php

namespace MatthewJensen\LaravelDiscourse;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\Registrar as Router;

class DiscourseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(
            __DIR__.'/../config/discourse.php', 'discourse'
        );
    }

    /**
     * Binds Discourse class to DiscourseInterface Contract
     * Adds sso routes.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__.'/../config/discourse.php' => config_path('discourse.php'),
        ], 'config');
        $this->app->singleton(\MatthewJensen\LaravelDiscourse\Contracts\ApiClient::class, function ($app) {
            $config = $app['config'];
            return new Discourse(
                $this->remove_http($config->get('discourse.url')), 
                $config->get('discourse.token')
            );
        });
        $this->app->singleton(\MatthewJensen\LaravelDiscourse\Contracts\SingleSignOn::class, function ($app) {
            $config = $app['config'];
            $sso = new SingleSignOn();
            $sso->setSecret($config->get('discourse.secret'));
            return $sso;
        });
        $this->loadRoutes();
    }

    private function loadRoutes() {
        $this->app['router']
            ->domain(
                $this->app['config']->get('discourse.domain')
            )
            ->middleware(
                $this->app['config']->get('discourse.middleware', ['web', 'auth'])
            )
            ->group(function (Router $router) {
                $router->get(
                    $this->app['config']->get('discourse.route'),
                    [
                        'uses' => 'MatthewJensen\LaravelDiscourse\Http\Controllers\DiscourseController@login',
                        'as'   => 'sso.login',
                        'middleware' => ['auth']//,'verified']
                    ]
                );
                $router->get(
                    $this->app['config']->get('discourse.logout'),
                    [
                        'uses' => 'MatthewJensen\LaravelDiscourse\Http\Controllers\DiscourseController@logout',
                        'as'   => 'sso.logout',
                    ]
                );
            });
    }
    // see: https://stackoverflow.com/questions/4357668/how-do-i-remove-http-https-and-slash-from-user-input-in-php
    private function remove_http($url) {
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
            if(strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }
}
