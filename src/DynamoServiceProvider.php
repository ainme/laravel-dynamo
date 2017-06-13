<?php
namespace Aimme\Dynamo;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Aimme\Dynamo\Console\GenerateMigrations;
use Generator\Generator;

class DynamoServiceProvider extends IlluminateServiceProvider
{
    protected $commands = [
        GenerateMigrations::class
    ];

    public function register()
    {
        $this->commands($this->commands);

        $configPath = __DIR__.'/../config/dynamo.php';
        $this->mergeConfigFrom($configPath, 'dynamo');

        $this->app->bind('dynamo.config', function(){
            return $this->app['config']->get('dynamo');
        });

        $this->app->bind('dynamo', function() {
            $configs = $this->app->make('dynamo.config');
            $generator = new Generator($configs);
            return $generator;
        });


    }

    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen') === true;
    }

    public function boot()
    {
        if (! $this->isLumen()) {
            $configPath = __DIR__.'/../config/dynamo.php';
            $this->publishes([$configPath => config_path('dynamo.php')], 'dynamo-config');
        }
    }
}
