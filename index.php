<?php

require __DIR__.'/vendor/autoload.php';

use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

$obPayload = (new Payload)->setPixKey('chillout1manager@gmail.com')
                          ->setDescription('pagamento do pedido 1541')
                          ->setMerchantname('Luis Gustavo')
                          ->setMerchantCity('ITAJAI')
                          ->setAmount(100.00)
                          ->setTxid('PIXTESTE123456');

$payloadQrCode = $obPayload->getPayload();

$obQrCode = new QrCode($payloadQrCode);

$image = (new Output\Png)->output($obQrCode, 400);

header('Content-Type: image/png');
echo $image;