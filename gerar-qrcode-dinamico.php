<?php

require __DIR__.'/vendor/autoload.php';
 
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

use \App\Pix\Api;
use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

$obApiPix = new Api(getenv('BASE_URL_API_SANDBOX'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'), getenv('CERTIFICADO'));

$request = [
    'calendario' => [
        'expiracao' => 3600
    ],
    'devedor' => [
        'cpf' => '12345678909',
        'nome' => 'luis gustavo'
    ],
    'valor' => [
        'original' => '10.00'
    ],
    'chave' => 'chillout1manager@gmail.com',
    'solicitacaoPagador' => "Pagamento do pedido 32485"
];
$pedido = '123465';
$date = str_replace([' ', '-', ':', '/'], "", date("Y-m-d H:i:s"));
$txId = $pedido . 'U' .$date . uniqid();

$response = $obApiPix->createCob($txId, $request);

$obPayload = (new Payload)->setMerchantname('Luis Gustavo')
                          ->setMerchantCity('ITAJAI')
                          ->setAmount($response['valor']['original'])
                          ->setTxid($response['txid'])
                          ->setUrl($response['location'])
                          ->setUniquePayment(true);

$payloadQrCode = $obPayload->getPayload();

$obQrCode = new QrCode($payloadQrCode);

$image = (new Output\Png)->output($obQrCode, 400);

header('Content-Type: image/png');
echo $image;
exit;