<?php
namespace App\Services;

use App\Helpers\Api;
use App\Helpers\Payload;
use App\Helpers\FormatString;
use Illuminate\Support\Facades\DB;

class GenerateQrCodeService
{
    public function generate(
        int $expiracao,
        string $cpf = null,
        string $cnpj,
        string $valor,
        string $chave,
        string $merchant_city,
        string $txIdReq = null
        ) {
       $clientData = DB::table('pix')->where("pix_key","=",$chave)->first();
       
       if($clientData){
        $enterpriseName = DB::table('empresa')->select("razaosocial as nome")->where("codigo","=",$clientData->codempresa)->first();
        if($enterpriseName){
            if($clientData->bank_code = '001'){
                $merchant_city = FormatString::remove_accents($merchant_city);
                $merchant_city = strtoupper($merchant_city);
                $params = $this->createCob($expiracao,$cpf,$cnpj,$enterpriseName->nome,$valor,$chave,$txIdReq,$clientData->client_id,$clientData->client_secret);
                if(!isset($params->location)){
                $response['status'] = 400;
                $response['message'] = "Problemas ao gerar qr code dinamico";
                return $response;
                }
                $payload = (new Payload)
                    ->setMerchantName($cnpj)
                    ->setMerchantCity($merchant_city)
                    ->setAmount($params->valor->original)
                    ->setUrl($params->location);
                $response['payload'] = $payload->getPayload();
                $response['txid'] = $params->txid;
                return $response;
           }
        }
       }
    }

    private function createCob(
        int $expiracao,
        string $cpf = null,
        string $cnpj,
        string $devedor,
        string $valor,
        string $chave,
        string $txIdReq = null,
        string $client_id,
        string $client_secret

    )
    {
        $api = new Api(
            env('BASE_URL'),
            //Client Id
            env('CLIENT_ID'),
            //Client Secret
            env('CLIENT_SECRET'),
            //Certificado
            __DIR__ . '/files/certificates/bb.pem'

        );
        if($cnpj){
            $request = [
                "calendario" => [
                    "expiracao" => $expiracao,
                ],
                "devedor" => [
                    "cnpj" => $cnpj,
                    "nome" => $devedor,
                ],
                "valor" => [
                    "original" => $valor,
                ],
                "chave" => $chave,
                "solicitacaoPagador" => "Pagamento do pedido",
            ];
        }else if($cpf){
            $request = [
                "calendario" => [
                    "expiracao" => $expiracao,
                ],
                "devedor" => [
                    "cnpj" => $cnpj,
                    "nome" => $devedor,
                ],
                "valor" => [
                    "original" => $valor,
                ],
                "chave" => $chave,
                "solicitacaoPagador" => "Pagamento do pedido",
            ]; 
        }
       
        if($txIdReq){
            $txid = $txIdReq;
        }else{
            $txid = md5(uniqid(rand(26, 35), true));
        }
        $res = $api->createCob($txid,$request,$client_id,$client_secret);
        
        return $res;
    }
}
