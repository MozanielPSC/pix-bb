<?php
    namespace App\Services;
    use App\Helpers\Api;

    class CobService{
        public function updateCobStatus($txId,$pixStatus){
            $api = new Api(
                env('BASE_URL'),
                //Client Id
                env('CLIENT_ID'),
                //Client Secret
                env('CLIENT_SECRET'),
                //Certificado
                __DIR__ . '/files/certificates/bb.pem'
            );

            $request = [
                "status"=> $pixStatus
            ];
            $response =  $api->updateCob($txId,$request);
            return $response;
        }
        public function getOneCob($txId){
            $api = new Api(
                env('BASE_URL'),
                //Client Id
                env('CLIENT_ID'),
                //Client Secret
                env('CLIENT_SECRET'),
                //Certificado
                __DIR__ . '/files/certificates/bb.pem'
            );
            $response = $api->getCob($txId);
            return $response;
        }
    }