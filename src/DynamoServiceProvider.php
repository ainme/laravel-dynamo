<?php
namespace Aimme\Dynamo;

use Aimme\Dynamo\Console\GenerateMigrations;
use Aimme\Dynamo\LaravelMakeMigrations;
use Generator\Contracts\MakeableInterface;
use Generator\Generator;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

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

        $this->app->bind(MakeableInterface::class, LaravelMakeMigrations::class);

        $this->app->bind('dynamo', function() {
            $configs = $this->app->make('dynamo.config');
            $generator = new Generator($configs);
            return $generator;
        });


    }

    protected function isLumen()
    {
        return (strpos($this->app->version(), 'Lumen') !== false);
    }

    public function boot()
    {
        if (! $this->isLumen()) {
            $configPath = __DIR__.'/../config/dynamo.php';
            $this->publishes([$configPath => config_path('dynamo.php')], 'dynamo-config');
        }
    }
}
