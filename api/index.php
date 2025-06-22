<?php

// Ini adalah versi sederhana, sesuaikan dengan index.php Laravel Anda
// Biasanya ini akan memuat vendor/autoload.php dan bootstrap/app.php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, auto-loading mechanism for applications
| that will load all of your application's dependencies. Don't worry it
|'s surprisingly great! We just need to register it with the PHP host.
|
*/

require __DIR__ . "/../public/index.php";

require __DIR__.'/../vendor/autoload.php'; // Pastikan path ini benar relatif ke api/index.php

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php'; // Pastikan path ini benar

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);