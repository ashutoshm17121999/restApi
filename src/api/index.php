<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once "./vendor/autoload.php";

$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Handlers' => './handlers'
    ]
);

$loader->register();

$prod = new Api\Handlers\Product();
$order = new Api\Handlers\Order();
$container = new FactoryDefault();

$app = new Micro($container);

$app->before(
    function () use ($app) {
        if (!str_contains($_SERVER['REQUEST_URI'], 'tokenGenerate') && (!str_contains($_SERVER['REQUEST_URI'], 'order'))) {
            $token = $app->request->getQuery("token");
            if (!$token) {
                echo 'Provide token in URL"';
                die;
            }
            $key = 'example_key';
            try {
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
            } catch (\Firebase\JWT\ExpiredException $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
                die;
            }
            if ($decoded->role != 'admin') {
                echo 'You Are Not Authorized';
                die;
            }
        }
    }
);

$app->post(
    '/api/order/create',
    [
        $order,
        'orderCreate'
    ]
);

$app->get(
    '/api/invoices/view/{id}/{where}/{limit}/{page}',
    [
        $prod,
        'get'
    ]
);

$app->get(
    '/api/product/get/{per_page}/{page}',
    [
        $prod,
        'getProducts'
    ]
);

$app->get(
    '/api/product/search/{keyword}',
    [
        $prod,
        'searchProducts'
    ]
);

$app->get(
    '/api/tokenGenerate',
    [
        $prod,
        'tokenGenerate'
    ]
);
$app->put(
    '/api/order/update',
    [
        $order,
        'orderUpdate'
    ]
);
// $app->get(
//     '/api/order/create',
//     [
//         $order,
//         'createOrder'
//     ]
// );



$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client("mongodb://root:password123@mongo");

        return $mongo->store;
    },
    true
);


// $app->get(
//     '/products/search',
//     [
//         $prod,
//         'searchProducts'
//     ]
// );

// $app->get(
//     '/products/get/{per_page}/{page}',
//     [
//         $prod,
//         'getProducts'
//     ]
// );


$app->handle(
    $_SERVER['REQUEST_URI']
);
