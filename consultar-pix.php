<?php

require __DIR__.'/vendor/autoload.php';

use \App\Pix\Api;

$obApiPix = new Api('https://api.itau.com.br/sandbox/pix_recebimentos', 
                    '24bbabfe-5d8b-47a7-97f4-21d5b35b2828',
                    '3c25c2bb-a626-42c9-86f4-8019241e2fef',
                    'certificado');



$response = $obApiPix->consultCob($txId);

print_r($response);

exit;