<?php

namespace Api\Handlers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Http\Response;
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

use Phalcon\Di\Injectable;

class Order extends Injectable
{


    /**function to place order */
    public function orderCreate()
    {
        $token = $this->request->getQuery('token');
        $key = 'example_key';
        $decodedtoken = JWT::decode($token, new Key($key, 'HS256'));

        $response = new Response();
        $postdata = $this->request->getPost();

        $orderData = $this->mongo->orders->find()->toArray();
        $orderCount = count($orderData);
        // print_r($orderCount);
        // die;

        if (isset($postdata['name']) && isset($postdata['prod_id']) && isset($postdata['product_name']) && isset($postdata['quantity'])) {
            $data = array(
                "order_id" => strval($orderCount + 1),
                "customer_id" => $decodedtoken->id,
                "name" => $this->request->getPost('name'),
                "prod_id" => $this->request->getPost('prod_id'),
                "prod_name" => $this->request->getPost('product_name'),
                "quantity" => $this->request->getPost('quantity'),
                "status" => "paid"
            );
            // print_r($data);
            // die;
            $products = $this->mongo->product->findOne(['id' => $data['prod_id']]);

            // $productss = $this->mongo->products->find()->toArray();
            // $count = count(array_values($productss));
            // $products_id = $count + 1;
            // echo $products_id;
            // die();
            if (isset($products)) {
                $this->mongo->orders->insertOne($data);
            } else {
                $data = ["status" => 404, "data" => "product not found"];
                return $response->setJSONContent($data)->send();
            }
        } else {
            $data = ["status" => 404, "data" => "undefined data format"];
            return $response->setJSONContent($data)->send();
        }
    }

    /***function to update order */
    public function orderUpdate()
    {
        $response = new Response();
        if ($this->request->isPut()) {

            $status = $this->request->getPut('status');
            $id = $this->request->getPut('id');
            $orders = $this->mongo->orders->findOne(['order_id' => $id]);

            if (isset($orders)) {
                $this->mongo->orders->updateOne(["order_id" => $id], ['$set' => ['status' => $status]]);
            } else {
                $data = ["status" => 404, "data" => "order does not exist"];
                return $response->setJSONContent($data)->send();
            }
        }
    }
}
