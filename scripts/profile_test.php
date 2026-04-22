<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$login = Http::post('http://localhost:8000/api/v1/login', [
    'email' => 'jane@example.com',
    'password' => 'password'
]);
$token = $login->json('data.token');
echo "token= $token\n";

$res = Http::withToken($token)->get('http://localhost:8000/api/v1/profile');
echo "profile response:\n" . $res->body() . "\n";
