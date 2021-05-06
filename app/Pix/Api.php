<?php

namespace App\Pix;

class Api  
{
    /** 
     * @var string url base do PSP 
     * 
    */
    protected $baseUrl;

    /**
     *  @var string client ID  do PSP
     * 
     */
    protected $clientId;

    /**
     *  @var string client secret  do PSP
     * 
     */
    protected $clientSecret;

    /**
     *  @var string caminho absoluto até o arquivo do certificado
     * 
     */
    protected $certificate;

    public function __construct($baseUrl, $clientId, $clientSecret, $certificate)
    {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->certificate = $certificate;
    }
    
    /**
     * Responsável por criar uma cobrança imediata
     *
     * @param string $txId txID unico da cobrança
     * @param array $request Description
     * @return array
     * 
     **/
    public function createCob($txId, $request)
    {
        return $this->send('PUT', '/v1/cob/' . $txId, $request);
    }
    
    public function consultCob($txId)
    {
        return $this->send('GET', '/v1/cob/' . $txId);
    }

    public function getAccessTokenSandbox()
    {
        $endpoint =  'https://api.itau.com.br/sandbox/api/oauth/token';

        $headers = [
            'Content-type: application/x-www-form-urlencoded',
        ];

        $request = "grant_type=client_credentials&client_id={$this->clientId}&client_secret={$this->clientSecret}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);
        
        return $response['access_token'];

    }

    //responsavel por enviar requisições para o PSP
    private function send($method, $resource, $request = [])
    {   
        try {
        $authorization = "Authorization: Bearer {$this->getAccessTokenSandbox()}";
        $endpoint = $this->baseUrl . $resource;
        $headers = [
            'Cache-Control: no-cache',
            'Content-type: application/json',
            $authorization
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
        ]);

        switch ($method) {
            case 'POST':
            case 'PUT':
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
                break;
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);

        } catch (\Throwable $th) {
            return $th;
        }

    }
}
