<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Services\PixService;

    class Pix extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        private $payload;
        public function __construct()
        {

        }
        /**
         * Método responsável por consultar vários pix
         * @return json
        */
        public function multiplePix(Request $request)
        {
            $inicio = $request->query('inicio');
            $fim = $request->query('fim');
            $paginaAtual = $request->query('paginaAtual');
            $pixService = new PixService();
            $response = $pixService->getMultiplePix($inicio,$fim,$paginaAtual);
            return response()->json($response,200);
        }
        /**
         * Método responsável por consultar um pix
         * @param  string $e2eid
         * @return json
         */
        public function onePix($e2eid)
        {
            $pixService = new PixService();
            $response = $pixService->getOnePix($e2eid);
            return response()->json($response,200);
        }
        public function createDevolution(Request $request){
            $args = $request->all(['e2eid','id']);
            $response = $pixService->getOnePix($args['e2eid'],$args['id']);
            return response()->json($response,200);
        }
        public function getDevolution(Request $request){
            $args = $request->all(['e2eid','id']);
        }

        
        public function payPix(Request $request){
            $args = $request->all(['payload']);
            $pixService = new PixService();
            $response = $pixService->pay($args['payload']);
            return response()->json($response,200);
        }
}
