<?php
namespace App\Services;

use App\Helpers\Api;

class PixService
{
    public function getMultiplePix($inicio, $fim, $paginaAtual)
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
        $response = $api->getPixs($inicio, $fim, $paginaAtual);
        return $response;

    }
    public function getOnePix($e2eid)
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
        $response = $api->getPix($e2eid);
        return $response;
    }
    public function requestPixDevolution($e2eid, $id)
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
        $response = $api->createDevolution($e2eid, $id);
        return $response;

    }

    public function getPixDevolution($e2eid, $id)
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
        $response = $api->getDevolution($e2eid, $id);
        return $response;
    }

    public function pay($payload){
        $api = new Api(
            env('BASE_URL'),
            //Client Id
            env('CLIENT_ID'),
            //Client Secret
            env('CLIENT_SECRET'),
            //Certificado
            __DIR__ . '/files/certificates/bb.pem'
        );
        $requestArray = [
            "pix"=>$payload
        ];
        $response = $api->payPix($requestArray);
        return $response;
    }

}
