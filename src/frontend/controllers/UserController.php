<?php

// namespace Admin\Controllers;

use Phalcon\Mvc\Controller;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    public function indexAction()
    {
    }

    /**function to to signup a user */
    public function signupAction()
    {
        if ($this->request->getPost('register')) {
            $data = $this->request->getPost();
            // echo '<pre>';
            // print_r($data);
            // die;
            $this->mongo->users->insertOne($data);
            $user = $this->mongo->users->findOne(['email'=>$data['email']]);
            $id = strval($user->_id);

            $token = $this->tokenGet($id);
            echo $token;
            die;
        }
    }

    /**function to get a token */
    public function tokenGet($id)
    {
        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "exp" => time() * 24 + 3600,
            "role" => 'user',
            "id" => $id
        );
        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;
    }
}
