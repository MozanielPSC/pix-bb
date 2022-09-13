<?php

namespace App\Http\Controllers;
use App\Services\GenerateQrCodeService;
use App\Services\CobService;
use App\Helpers\Api;
use App\Helpers\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;
use Illuminate\Http\Request;
class Cob extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private  $payload;
    public function __construct()
    {
       
    }

    public function dinamic(Request $request){
        $args = $request->all(['expiracao','cnpj','cpf','valor','chave','cidade','txid']);
        if(
        !$args['expiracao'] ||
        !$args['cnpj'] ||
        !$args['valor'] ||
        !$args['chave'] ||
        !$args['cidade']
        ){
            $response["status"] = 400;
            $response["message"] = "Envie parâmetros válidos";
            return response()->json($response,200);
        }else{
            $qrCodeGenerator = new GenerateQrCodeService();
            $res = $qrCodeGenerator->generate(
                $args['expiracao'],
                $args['cpf'],
                $args['cnpj'],
                $args['valor'],
                $args['chave'],
                $args['cidade'],
                $args['txid']
            );
            if(isset($res['status'])){
                return response()->json($res,400);
            }else{
                $response['status'] = 200;
                $response['payload'] = $res['payload'];
                $response['txid'] = $res['txid'];
                $final = json_encode($response,JSON_UNESCAPED_SLASHES);
                return response($final,200);
            }    
        }
                 
    }  
    public function updateCob($txId,Request $request){
        $args = $request->all(['status']);
        $cobService = new CobService();
        $response =  $cobService->updateCobStatus($txId,$args['status']);
        return response()->json($response,200);
    }

    public function getCob($txId){
        $cobService = new CobService();
        $response =  $cobService->getOneCob($txId);
        return response()->json($response,200);
    }
}
