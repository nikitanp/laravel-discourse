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
        ]);
        $this->app->singleton(\MatthewJensen\LaravelDiscourse\Contracts\ApiClient::class, function ($app) {
            $config = $app['config'];
            return new Discourse($config->get('discourse.url'), $config->get('discourse.token'));
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
        $this->app['router']->group(
            ['middleware' => $this->app['config']->get('discourse.middleware', ['web', 'auth'])],
            function (Router $router) {
                $router->get(
                    $this->app['config']->get('discourse.route'),
                    [
                        'uses' => 'MatthewJensen\LaravelDiscourse\Http\Controllers\DiscourseController@login',
                        'as'   => 'sso.login',
                        'middleware' => ['auth','verified']
                    ]
                );
                $router->get(
                    $this->app['config']->get('discourse.logout'),
                    [
                        'uses' => 'MatthewJensen\LaravelDiscourse\Http\Controllers\DiscourseController@logout',
                        'as'   => 'sso.logout',
                    ]
                );
            }
        );
    }
}
