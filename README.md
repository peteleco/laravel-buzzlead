Este pacote integra o laravel com a plataforma de indicações [Buzzlead](https://buzzlead.com.br)


## Installation
You can install this package via composer using this command:
````
composer require peteleco/laravel-buzzlead
````

Next, you must install the service provider:

// config/app.php
````
'providers' => [
    ...
    Peteleco\Buzzlead\BuzzleadServiceProvider::class,
];
````
You can publish the migration with:
````
php artisan vendor:publish --provider="Peteleco\Buzzlead\BuzzleadServiceProvider" --tag="migrations"
````

You can publish the config-file with:
````
php artisan vendor:publish --provider="Peteleco\Buzzlead\BuzzleadServiceProvider" --tag="config"
````
This is the contents of the published config file:

````
return [
    'api' => [
        'token' => env('BUZZLEAD_API_TOKEN', ''),
        'key'   => env('BUZZLEAD_API_KEY', '')
    ]
];
````
