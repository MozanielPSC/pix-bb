<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use Guzzle\Exception\GuzzleException;

class Api
{

    /**
     * URL base do PSP
     * @var string
     */
    private $baseUrl;

    /**
     * Client ID do oAuth2 do PSP
     * @var string
     */
    private $clientId;

    /**
     * Client secret do oAuht2 do PSP
     * @var string
     */
    private $clientSecret;

    /**
     * Caminho absoluto até o arquivo do certificado
     * @var string
     */
    private $certificate;

    /**
     * Define os dados iniciais da classe
     * @param string $baseUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $certificate
     */
    public function __construct($baseUrl, $clientId, $clientSecret, $certificate)
    {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->certificate = $certificate;
    }

    /**
     * Método responsável por criar uma cobrança imediata
     * @param  string $txid
     * @param  array $request
     * @return array
     */
    public function createCob($txid, $request,$client_id,$client_secret)
    {
        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => "Bearer " . $this->getAccessToken($client_id,$client_secret),
                    'Cache-Control' => 'no-cache',
                    'Content-type' => 'application/json',
                ],
                /* Desativar SSL*/
                'verify' => false,
            ]);
            /* Requisição PUT*/
            $response = $guzzle->request(
                'PUT', env('BASE_URL') . '/cob/' . $txid . '?gw-dev-app-key=' . env('DEVELOPER_KEY'),
                [
                    'json' => $request,
                ]);
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        }
    }
    public function payPix($request){

        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => "Bearer " . $this->getAccessToken(),
                    'Cache-Control' => 'no-cache',
                    'Content-type' => 'application/json',
                ],
                /* Desativar SSL*/
                'verify' => false,
            ]);
            /* Requisição POST*/
            $response = $guzzle->request(
                'POST', env('BASE_URL_PIX_PAYMENT').'/boletos-pix/pagar'.'?gw-app-key=95cad3f03fd9013a9d15005056825665',
                [
                    'json' => $request,
                ]);
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        } 
    }
    /**
     * Método responsável por consultar uma cobrança
     * @param  string $txid
     * @return array
     */
    public function getCob($txId)
    {
        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => "Bearer " . $this->getAccessToken(),
                    'Cache-Control' => 'no-cache',
                    'Content-type' => 'application/json',
                ],
                /* Desativar SSL*/
                'verify' => false,
            ]);
            /* Requisição PATCH*/
            $response = $guzzle->request(
                'GET', env('BASE_URL') . '/cob/' . $txId . '?gw-dev-app-key=' . env('DEVELOPER_KEY')
            );
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();
            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();
            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        }
    }
    /**
     * Método responsável por atualizar uma cobrança
     * @param  string $txid
     * @return array
     */
    public function updateCob($txid, $request)
    {
        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => "Bearer " . $this->getAccessToken(),
                    'Cache-Control' => 'no-cache',
                    'Content-type' => 'application/json',
                ],
                /* Desativar SSL*/
                'verify' => false,
            ]);
            /* Requisição PATCH*/
            $response = $guzzle->request(
                'PATCH', env('BASE_URL') . '/cob/' . $txid . '?gw-dev-app-key=' . env('DEVELOPER_KEY'),
                [
                    "json" => $request,
                ]
            );
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();
            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();
            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        }
    }
    public function getPix($e2eid)
    {
        $guzzle = new Client([
            'headers' => [
                'Authorization' => "Bearer " . $this->getAccessToken(),
                'Cache-Control' => 'no-cache',
                'Content-type' => 'application/json',
            ],
            /* Desativar SSL*/
            'verify' => false,
        ]);
        try {
            /* Requisição PATCH*/
            $response = $guzzle->request(
                'GET', env('BASE_URL') . '/cob/' . $e2eid . '?gw-dev-app-key=' . env('DEVELOPER_KEY')
            );
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();
            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();
            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        }
    }
    public function getPixs($inicio, $fim, $paginaAtual)
    {
        try {
            if (isset($inicio)) {
                $queryInicio = '&inicio=' . $inicio;
            } else {
                $queryInicio = '';
            }
            if (isset($fim)) {
                $queryFim = '&fim=' . $fim;
            } else {
                $queryFim = '';
            }
            if (isset($paginaAtual)) {
                $queryPagina = '&paginaAtual=' . $paginaAtual;
            } else {
                $queryPagina = '';
            }
            $queryTotal = $queryInicio . $queryFim . $queryPagina;
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => "Bearer " . $this->getAccessToken(),
                    'Cache-Control' => 'no-cache',
                    'Content-type' => 'application/json',
                ],
                /* Desativar SSL*/
                'verify' => false,
            ]);
            /* Requisição PATCH*/
            $response = $guzzle->request(
                'GET', env('BASE_URL') . '/pix' . '?' . '&gw-dev-app-key=' . env('DEVELOPER_KEY') . $queryTotal
            );
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();
            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();
            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            dd($final);
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        }

    }
    public function createDevolution($e2eid, $id)
    {
        $guzzle = new Client([
            'headers' => [
                'Authorization' => "Bearer " . $this->getAccessToken(),
                'Cache-Control' => 'no-cache',
                'Content-type' => 'application/json',
            ],
            /* Desativar SSL*/
            'verify' => false,
        ]);
        try {
            /* Requisição PATCH*/
            $response = $guzzle->request(
                'PUT', env('BASE_URL') . '/pix/' . $e2eid . '/devolucao/' . $id . '?gw-dev-app-key=' . env('DEVELOPER_KEY')
            );
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();
            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();
            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        }
    }
    public function getDevolution($e2eid, $id)
    {
        $guzzle = new Client([
            'headers' => [
                'Authorization' => "Bearer " . $this->getAccessToken(),
                'Cache-Control' => 'no-cache',
                'Content-type' => 'application/json',
            ],
            /* Desativar SSL*/
            'verify' => false,
        ]);
        try {
            /* Requisição PATCH*/
            $response = $guzzle->request(
                'GET', env('BASE_URL') . '/pix/' . $e2eid . '/devolucao/' . $id . '?gw-dev-app-key=' . env('DEVELOPER_KEY')
            );
            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();
            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();
            /* Converte o JSON em array associativo PHP */
            $final = json_decode($contents);
            return $final;
        } catch (GuzzleException $e) {
            $e->getMessage();
        }
    }
    public function getAccessToken($client_id =null,$client_secret = null)
    {
        if(!$client_id || !$client_secret){
          $client_id =   env('CLIENT_ID');
          $client_secret = env('CLIENT_SECRET');
        }
        try {
            /* Criação do objeto cliente */
            $guzzle = new Client([
                'headers' => [
                    'gw-dev-app-key' => env('DEVELOPER_KEY'),
                    'Authorization' => env('BASIC'),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                /* Desativar SSL*/
                'verify' => false,
            ]);
            /* Requisição POST*/
            $response = $guzzle->request('POST', 'https://oauth.sandbox.bb.com.br/oauth/token?gw-dev-app-key=' . env('DEVELOPER_KEY'),
                array(
                    'form_params' => array(
                        'grant_type' => 'client_credentials',
                        'client_id' => $client_id,
                        'client_secret' => $client_secret,
                        'scope' => 'cob.write cob.read pix.read pix.write',
                    )));

            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converte o JSON em array associativo PHP */
            $token = json_decode($contents);
            return (string) $token->access_token;

        } catch (GuzzleException $e) {
            $e->getMessage();
        }
    }
}
