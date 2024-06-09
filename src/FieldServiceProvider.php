<?php

namespace Gromatics\LaravelNovaUnsplashMediaLibrary;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('laravel-nova-unsplash-media-library', __DIR__.'/../dist/js/field.js');
            Nova::style('laravel-nova-unsplash-media-library', __DIR__.'/../dist/css/field.css');
        });

        $this->publishes([__DIR__ . '/../config/unsplash-media-library.php' => config_path('unsplash-media-library.php')]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app['config']->get('unsplash-media-library') === null) {
            $this->app['config']->set('unsplash-media-library', require __DIR__.'/../config/unsplash-media-library.php');
        }
    }
}
