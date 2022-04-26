<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
        $data = $this->mongo->orders->find();
        $this->view->orders = $data;
    }
}
