<?php

namespace NikitaMikhno\LaravelDiscourse;

use Illuminate\Contracts\Routing\Registrar as Router;
use Illuminate\Support\ServiceProvider;
use NikitaMikhno\LaravelDiscourse\Contracts\ApiClient as ApiClientContract;
use NikitaMikhno\LaravelDiscourse\Contracts\SingleSignOn as SingleSignOnContract;

class DiscourseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/discourse.php',
            'discourse'
        );
    }

    /**
     * Binds Discourse class to DiscourseInterface Contract
     * Adds sso routes.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../config/discourse.php' => config_path('discourse.php'),
            ],
            'config'
        );

        $this->app->singleton(
            ApiClientContract::class,
            function () {
                return new Discourse(
                    $this->remove_http($this->app['config']->get('discourse.url')),
                    $this->app['config']->get('discourse.token')
                );
            }
        );

        if ($this->app['config']->get('discourse.sso_enabled', false)) {
            $this->app->singleton(
                SingleSignOnContract::class,
                function () {
                    $sso = new SingleSignOn();
                    $sso->setSecret($this->app['config']->get('discourse.secret'));

                    return $sso;
                }
            );

            $this->loadRoutes();
        }
    }

    private function loadRoutes(): void
    {
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
                        'uses' => 'NikitaMikhno\LaravelDiscourse\Http\Controllers\DiscourseController@login',
                        'as' => 'sso.login',
                        'middleware' => ['auth']
                    ]
                );
                $router->get(
                    $this->app['config']->get('discourse.logout'),
                    [
                        'uses' => 'NikitaMikhno\LaravelDiscourse\Http\Controllers\DiscourseController@logout',
                        'as' => 'sso.logout',
                    ]
                );
            });
    }

    /** @noinspection HttpUrlsUsage */
    private function remove_http(string $url): string
    {
        $disallowed = array('http://', 'https://');

        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }
}
