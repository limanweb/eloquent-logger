# eloquent-logger

## Installation

1. Run command to install a package into you project

	composer require limanweb/eloquent-logger

2. Add package provider into `providers` section of your `config/app.php`

	'providers' => [
		...
		\Limanweb\EloquentLogger\ServiceProvider::class,
	],

3. Run command to publish package's config and migration

	php artisan vendor:publish

and choise package `\Limanweb\EloquentLogger\ServiceProvider` to publish.	

Now you have configuration file `config/limanweb/eloquent_logger.php`.

4. If you have changed user ID type to UUID in your project, then you must configure it in `user` section of configuration.

	'user' => [
		...
		'key_cast' => 'string',
		'key_create_method' => 'uuid',
	],
	
5. Run migrate command.

	php artisan migrate
	
6. In `App\Providers\EventServiceProvider` add next line to bottom of `boot()`

    \Limanweb\EloquentLogger\LoggerService::initLogger();
	
## Configuration

To turn on logger fore any model, add it into `models` section of configutarion.

	'models' => [
		...
		App\AnyModel::class => [],
	],	 

To exclude any model specified fields, add if into `models.AnyModel.exclude_fields` array of model declaration.

	'models' => [
		...
		App\AnyModel::class => [
			'exclude_fields' => [
				'search',
			],
		],
	],	 

To globally exclude any fields, add if into `exclude_fields` array of configutarion.

	'exclude_fields' => [
		'created_at',
		'updated_at',
	],

	