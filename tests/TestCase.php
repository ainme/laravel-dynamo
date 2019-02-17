<?php

namespace Aimme\Dynamo\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
	{
	    return ['Aimme\Dynamo\DynamoServiceProvider'];
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
	    $app['config']->set('dynamo.config', include __DIR__ . "/../config/dynamo.php");
	}
}
