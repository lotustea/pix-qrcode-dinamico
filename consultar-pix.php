<?php

require __DIR__.'/vendor/autoload.php';

 
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

use \App\Pix\Api;

$obApiPix = new Api(getenv('BASE_URL_API_SANDBOX'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'), getenv('CERTIFICADO'));


$response = $obApiPix->consultCob($txId);

print_r($response);

exit;