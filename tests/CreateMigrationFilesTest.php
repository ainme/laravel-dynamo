<?php

namespace Aimme\Dynamo\Tests;

use Aimme\Dynamo\Tests\TestCase;

class CreateMigrationFilesTest extends TestCase
{
	protected function setUp(): void
    {
        parent::setUp();

	    $this->clean();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
    	parent::tearDown();

	    $this->clean();
    }

	/**
	 * @test
	 */
	public function generates_migration_files()
	{

		$migrationsPath = './tests/migrations';

		$this->deleteFolder($migrationsPath);

		$this->assertTrue(!file_exists($migrationsPath), 'migrations folder still exists!.');

	    $this->artisan('generate:migrations', [
	        'sqlPath' => __DIR__ . '/mysql/mysql.sql',
	        '--path' => $migrationsPath
	    ]);


	    $migrationFiles = glob($migrationsPath . "/" . date('Y_m_d_') . "*.php");
	    $this->assertEquals(count($migrationFiles), 10, 
	    	"migration files in folder count(".count($migrationFiles).") does not match to expected 10!");

	}

	/**
	 * path starts from root
	 * @param  string $path absolute path to folder (recursive not supported)
	 * @return void
	 */
	protected function deleteFolder($path)
	{
		if (file_exists($path)) {
			array_map('unlink', glob("$path/*.*"));
			rmdir($path);
		}
	}

	protected function clean()
	{
		$this->deleteFolder('./tests/migrations');
		if (file_exists('formatted_source.txt')) {
			unlink('formatted_source.txt');
		}
	}
}
