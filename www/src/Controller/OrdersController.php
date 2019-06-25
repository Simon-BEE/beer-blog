<?php
namespace App\Controller;

use \Core\Controller\Controller;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->loadModel('post');
        $this->loadModel('beer');
        $this->loadModel('orders');
    }

    public function order()
    {
        $beers = $this->beer->all();
        $user = $_SESSION['auth'];
        $title = 'Commander';
        if (empty($_POST)) {
            $this->render(
                'orders/order',
                [
                    "title" => $title,
                    "beers" => $beers,
                    "user" => $user
                ]
            );
        }
        

        if(isset($_POST)  && !empty($_POST)) {
            $auth = $_SESSION['auth'];
            $beerArray = $this->beer->all();
            $quantity = $_POST['qty'];
            $id_user = $auth->getId();
            $this->orders->purchase($beerArray, $quantity, $id_user);
            $this->render(
                'orders/order',
                [
                    "title" => $title,
                    "beers" => $beers,
                    "user" => $user
                ]
            );
        }
    }

    public function confirm($id, $id_user)
    {
        $order = $this->orders->find($id);
        $products = unserialize($order->getProducts());

        if (!$order) {
            throw new \Exception('Aucun article ne correspond à cet ID');
        }

        if ($order->getIdUser() !== $id_user) {
            $url = $this->generateUrl('orders', ['id' => $id, 'id_user' => $order->getIdUser()]);

            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }
        $this->render(
            'orders/confirm',
            [
                "title" => $title,
                "order" => $order,
                "products" => $products
            ]
        );
    }
}