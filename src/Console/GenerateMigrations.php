<?php

namespace Aimme\Dynamo\Console;

use Illuminate\Container\Container;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Config;

class GenerateMigrations extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'generate:migrations {sqlPath? : The path of the sql file.}
    {--path= : The location where the migration file should be created.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates migration files from sql';

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationCreator  $creator
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(Composer $composer, Container $app)
    {
        parent::__construct();

        $this->composer = $composer;
        $this->app = $app;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $sqlPath = trim($this->input->getArgument('sqlPath'));

        $this->generateMigrations($sqlPath);

        $this->composer->dumpAutoloads();
    }

    /**
     * for laravel 5.7+
     * @return void
     */
    public function handle()
    {
        return $this->fire();
    }

    /**
     * Write the migrations to disk.
     *
     * @param  string  $path
     * @return string
     */
    protected function generateMigrations($sqlPath = null)
    {
        if ($sqlPath) {
            Config::set('dynamo.migrations.source.raw', $sqlPath);
        }

        if ($this->getMigrationPath()) {
            Config::set('dynamo.migrations.make.output_path', $this->getMigrationPath());
        }

        $this->app['dynamo']->generateMigrations();

        $this->line("<info>Created Migration files:</info>");
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        if (! is_null($targetPath = $this->input->getOption('path'))) {
            return $targetPath;
        }

        return parent::getMigrationPath();
    }
}
