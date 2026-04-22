<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

// login and upload a small dummy image
$login = Http::post('http://localhost:8000/api/v1/login', [
    'email' => 'jane@example.com',
    'password' => 'password'
]);
$token = $login->json('data.token');
echo "token= $token\n";

// create dummy image file
$tmp = tempnam(sys_get_temp_dir(), 'img');
file_put_contents($tmp, base64_decode(
    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAA' .
    'WgmWQ0AAAAASUVORK5CYII='
));

$response = Http::withToken($token)->attach('avatar', file_get_contents($tmp), 'avatar.png')
    ->post('http://localhost:8000/api/v1/profile/avatar');

echo 'status=' . $response->status() . "\n";
echo $response->body() . "\n";
