<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'http://localhost:8000/storage/prescriptions/prescription_2_69aeb6c138789.pdf';
$ctx = stream_context_create(['http'=>['method'=>'GET','ignore_errors'=>true]]);
$resp = file_get_contents($url,false,$ctx);
$meta = $http_response_header;
echo "Status headers:\n";
print_r($meta);
echo "Body length: ".strlen($resp)."\n";
