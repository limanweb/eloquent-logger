# eloquent-logger

## Installation

Run command to install a package into you project

	composer require limanweb/eloquent-logger

Add package provider into `providers` section of your `config/app.php`

	'providers' => [
		...
		\Limanweb\EloquentLogger\ServiceProvider::class,
	],

Run command to publish package's config and migration

	php artisan vendor:publish --provider=Limanweb\EloquentLogger\ServiceProvider
	
Now you have configuration file `config/limanweb/eloquent_logger.php`.

If you have changed user ID type to UUID in your project, then you must configure it in `user` section of configuration.

	'user' => [
		...
		'key_cast' => 'string',
		'key_create_method' => 'uuid',
	],
	
Run migrate command.

	php artisan migrate
	
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

	