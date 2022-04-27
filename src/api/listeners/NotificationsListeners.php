<?php

namespace MyEventsHandler;

use GuzzleHttp\Client;
use Phalcon\Di\Injectable;

class NotificationsListeners extends Injectable
{
    public function reducePlaceOrder($event, $obj, $postArr)
    {
        // echo "hii";
        // die;
        // $events = $this->mongo->webhooks_collection->find(['event' => 'reducePlaceOrder']);
        $url = 'http://192.168.2.14:8080/frontend/order/display';
        // foreach ($events as $value) {
            // }
            $client = new Client();
            $client->request('POST', $url, ['form_params' => $postArr]);
    }
}
