<?php


namespace Biigle\Modules\abysses;

use Biigle\Http\Requests\UpdateUserSettings;
use Biigle\Modules\abysses\Events\AbyssesJobContinued;
use Biigle\Modules\abysses\Events\AbyssesJobCreated;
use Biigle\Modules\abysses\Events\AbyssesJobDeleting;
use Biigle\Modules\abysses\Listeners\DispatchAbyssesJob;
use Biigle\Services\Modules;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Event;

class AbyssesServiceProvider extends ServiceProvider
{

   /**
     * Bootstrap the application events.
     *
     * @param  \Biigle\Services\Modules  $modules
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
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
            'apidoc' => [__DIR__.'/Http/Controllers/Api/'],
        ]);

        if (config('abysses.notifications.allow_user_settings')) {
            $modules->registerViewMixin('abysses', 'settings.notifications');
            UpdateUserSettings::addRule('abysses_notifications', 'filled|in:email,web');
        }

        Gate::policy(AbyssesJob::class, Policies\AbyssesJobPolicy::class);
        Event::listen(AbyssesJobCreated::class, DispatchAbyssesJob::class);
        
    }

    /**
    * Register the service provider.
    *
    * @return  void
    */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/abysses.php', 'abysses');

        $this->app->singleton('command.abysses.publish', function ($app) {
            return new \Biigle\Modules\abysses\Console\Commands\Publish;
        });
        $this->commands('command.abysses.publish');

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
        ];
    }

}
