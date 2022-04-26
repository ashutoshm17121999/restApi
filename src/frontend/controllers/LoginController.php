<?php

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    /**function to get login */
    public function indexAction()
    {
        if ($this->request->getPost('login')) {
            $data = array(
                "email" => $this->request->getPost('email'),
                "password" => $this->request->getPost('password')
            );
            $users = $this->mongo->users->findOne(["email" => $data['email'], "password" => $data['password']]);
            if (empty($data['email']) || empty($data['password'])) {
                $this->view->loginmsg = "Please fill all fields";
            } elseif (!$users) {
                $this->view->loginmsg = "User Does not exist";
            } else {
                $this->response->redirect('/orders/index');
            }
        }
    }
}
