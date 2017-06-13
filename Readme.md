# Laravel Dynamo
The package generates migration files for the application using raw mysql file. 
I use this package to create migration files for the applications after designing and exporting my ERDs to raw mysql from mysql workbench. I have just used this on Laravel 5 and hopefully so far it works.

## Installation

Require this package in your `composer.json` and update composer

```php
"aimme/laravel-dynamo": "1.0.0"
```
After updating composer, add the ServiceProvider to the providers array in `config/app.php`

```php
Aimme\Dynamo\DynamoServiceProvider::class,
```	

To publish the config settings in Laravel 5 use:

```php
php artisan vendor:publish --provider="Aimme\Dynamo\DynamoServiceProvider"
```

The generator is bound to the ioC as `dynamo`

```php
$generator = App::make('dynamo');
```

## Documentation	

- configure the paths in `config/dynamo.php` . Sample values has already been provided

```php
	'migrations' => [
	     //path to the source erd sql export file
		'source' => [
			'raw' => 'mysql.sql', //source export file by default app will look for the file project root folder.
			'formatted' => 'formatted_source.txt' //to store the formatted source, by default the file will be created in project root
			],
		//make: this array is loaded to the MakeMigraions as making migrations configs
		//order: tells the order of making migrations
		//id_as_auto_increment:  true means if id is set in a table it would be set as increments()
		//or bigIncrements() depending on the field type declared.
		//timestamps_for_all: adds timestamps()->useCurrent() to all migrations even if its not declared in dump
		//output_path: to where all the migrations to be created. Changing it from here wouldn't bring any changes while running through artisan. Just use --path option to define path other than app defined path (database/migrations).
		//clean_folder: removes all the previous file in the path, if any
		'make' => [
			'order' => [
				// 'users',
	            'items_features',
	            // 'listings',
	            'sellers',
	            // 'features',
	            // 'items'
			],
			'id_as_auto_increment' => true,
			'timestamps_for_all' => true,
			'include_remaining' => true,
			'output_path' => 'database/migrations', 
			'clean_folder' => true
		],
	]
```

after configuring run the artisan command to generate the migration files from the sql file

```php
php artisan generate:migrations
```
a parameter is available to change the path of the sql file, if not defined it will look into file path defined in the configuration array [migrations] [source] [raw]. 
For more information see the above configuration file. By default you can put the mysql file named mysql.sql in the root folder, without bringing any changes to the configuration.
	
example: 
```php
php artisan generate:migrations mysqlfile.sql
```
--path option can be used to define path other than app defined migrations path (database/migrations). The location where the migration files should be created in.

example: 
```php
php artisan generate:migrations mysqlfile.sql --path=another/migrations
```
