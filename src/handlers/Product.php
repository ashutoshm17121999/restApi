<?php

namespace Api\Handlers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Phalcon\Di\Injectable;


class Product extends Injectable
{
    function get($select = "", $where = "", $limit = 10, $page = 1)
    {
        $products = array(
            array('select' => $select, 'where' => $where, 'limit' => $limit, 'page' => $page),
            array('name' => 'Product 2', 'price' => 40)
        );
        return json_encode($products);
    }




/**This function is used to get product through mongodb   */

    function getProducts($per_page = 10, $page = 1)
    {
        $collection = $this->mongo->product->find();
        // foreach ($collection as $k => $v) {
        //     echo '<pre>';
        //     echo $v->brand;
        $array = $collection->toArray();
        return json_encode($array);
    }



/**This Function is used to search the products through keywords  */

    function searchProducts($keyword = "")
    {
        $keywords = explode(" ", urldecode($keyword));
        $array = [];
        foreach ($keywords as $value) {
            $products = $this->mongo->product->find(
                [
                    'name' => [
                        '$regex' => $value,
                        '$options' => '$i'
                    ]
                ]
            );
            array_push($array, $products->toArray());
        }
        return json_encode($array);
    }



    /**This function is used to genrate token*/
    
    function tokenGenerate()
    {
        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "exp" => time() * 24 + 3600,
            "role" => 'user'
        );
        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;
    }
}
