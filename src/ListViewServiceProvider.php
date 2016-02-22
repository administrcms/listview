<?php

namespace Administr\Listview;


use Illuminate\Support\ServiceProvider;

class ListViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'administr.listview');

        $this->publishes([
            __DIR__ . '/Config/administr.listview.php' => config_path('administr.listview.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/administr.listview.php', 'administr.listview');
    }
}