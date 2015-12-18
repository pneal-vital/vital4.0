<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    'Illuminate\Contracts\Http\Kernel',
    'App\Http\Kernel'
);

$app->singleton(
    'Illuminate\Contracts\Console\Kernel',
    'App\Console\Kernel'
);

$app->singleton(
    'Illuminate\Contracts\Debug\ExceptionHandler',
    'App\Exceptions\Handler'
);

/*
|--------------------------------------------------------------------------
| Custom Monolog Configuration
|--------------------------------------------------------------------------
|
| See: http://laravel.com/docs/5.1/errors
|      https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md
|      https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md
|      http://laravel-tricks.com/tricks/monolog-for-custom-logging
|      https://laracasts.com/discuss/channels/general-discussion/advance-logging-with-laravel-and-monolog
*/

$app->configureMonologUsing(function($monolog) {
    $handler = new Monolog\Handler\RotatingFileHandler(storage_path().'/logs/laravel.log', 0, Monolog\Logger::DEBUG);
    //$handler->setFormatter(new Monolog\Formatter\LineFormatter(
    $handler->setFormatter(new App\LineNormalizer(
        "[%datetime%] %extra.process_id% %channel%.%level_name% %extra.class%::%extra.function%(%extra.line%): %message% %context%\n",
        null, true, true
    ));
    $handler->pushProcessor(new Monolog\Processor\ProcessIdProcessor());
    $handler->pushProcessor(new Monolog\Processor\IntrospectionProcessor(
        Monolog\Logger::DEBUG, ["Illuminate\\Support\\Facades\\Log", "Illuminate\\Support\\Facades\\Facade", "Illuminate\\Log\\Writer"]
    ));
    $monolog->pushHandler($handler);
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
