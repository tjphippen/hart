<?php namespace Tjphippen\Hart;

use Illuminate\Support\ServiceProvider;

class HartServiceProvider extends ServiceProvider
{
    /**
     *  Bootstrap the application events.
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('hart.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['hart'] = $this->app->share(function($app)
        {
            $config = $app->config->get('hart', array());
            return new Hart($config);
        });

        // Auto boot the facade.
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Hart', 'Tjphippen\Hart\Facades\Hart');
        });
    }

}