<?php
/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\ExampleController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('dinamic','Cob@dinamic');
$router->post('updateCob/{txId}','Cob@updateCob');
$router->get('getCob/{txId}','Cob@getCob');
$router->get('multiplePix','Pix@multiplePix');
$router->get('onePix/{e2eid}','Pix@getOnePix');
$router->post('payPix','Pix@payPix');
