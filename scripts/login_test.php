<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$response = Http::post('http://localhost:8000/api/v1/login', [
    'email' => 'jane@example.com',
    'password' => 'password'
]);
echo $response->body();
