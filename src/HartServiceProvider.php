<?php namespace Tjphippen\Hart;

use Illuminate\Support\ServiceProvider;

class HartServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('hart.php'),
        ]);
    }

    public function register()
    {
        $this->app['hart'] = $this->app->share(function($app)
        {
            $config = $app->config->get('hart', array());
            return new Hart($config);
        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Hart', 'Tjphippen\Hart\Facades\Hart');
        });
    }

}