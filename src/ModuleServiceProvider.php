<?php

namespace Biigle\Modules\abysses;

use Biigle\Services\Modules;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class ModuleServiceProvider extends ServiceProvider
{

   /**
   * Bootstrap the application events.
   *
   * @param Modules $modules
   * @param  Router  $router
   * @return  void
   */
    public function boot(Modules $modules, Router $router)
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'abysses');
        $this->loadMigrationsFrom(__DIR__.'/DataBase/migrations');
        
        $this->publishes([
            __DIR__.'/public/assets' => public_path('vendor/zuoyu2524/abysses'),
        ], 'public');

        $router->group([
            'namespace' => 'Biigle\Modules\abysses\Http\Controllers',
            'middleware' => 'web',
        ], function ($router) {
            require __DIR__.'/Http/routes.php';
        });

        $modules->register('abysses', [
            'viewMixins' => [
                'volumesSidebar',
                'manualTutorial',
                'manualReferences',
            ],
            'controllerMixins' => [
                //
            ],
            'apidoc' => [__DIR__.'/Http/Controllers/Api/'],
        ]);

        
    }

    /**
    * Register the service provider.
    *
    * @return  void
    */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/abysses.php', 'abysses');
        /*$this->app->singleton('command.abysses.publish', function ($app) {
            return new \Biigle\Modules\Abysses\Console\Commands\Publish;
        });
        $this->commands('command.abysses.publish');

        $this->app->singleton('command.abysses.migrate-patch-storage', function ($app) {
            return new \Biigle\Modules\Abysses\Console\Commands\MigratePatchStorage;
        });
        $this->commands('command.abysses.migrate-patch-storage');*/
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.abysses.publish',
            'command.abysses.migrate-patch-storage',
        ];
    }

}
