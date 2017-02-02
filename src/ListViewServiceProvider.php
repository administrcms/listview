<?php

namespace Administr\ListView;


use Administr\Filters\FiltersServiceProvider;
use Administr\ListView\Commands\MakeListView;
use Illuminate\Support\ServiceProvider;

class ListViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'administr/listview');

        $this->publishes([
            __DIR__ . '/Config/administr.listview.php' => config_path('administr.listview.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/administr/listview')
        ], 'views');
        
        $this->publishes([
            __DIR__ . '/Lang' => resource_path('lang')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(FiltersServiceProvider::class);

        $this->mergeConfigFrom(__DIR__ . '/Config/administr.listview.php', 'administr.listview');

        $this->commands([
            MakeListView::class,
        ]);
    }
}
