<?php
namespace App\Controller;

use \Core\Controller\Controller;
use Core\Controller\Helpers\MailController;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->loadModel('beer');
        $this->loadModel('user');
        $this->loadModel('user_infos');
        $this->loadModel('orders');
        $this->loadModel('orders_line');
        $this->loadModel('status');
        $this->loadModel('config');
    }

    public function order()
    {
        $lines = $this->orders_line->findByToken($_SESSION['token']);
        $user = $_SESSION['auth'];
        $title = 'Panier';
        return $this->render(
            'orders/order',
            [
                "title" => $title,
                "lines" => $lines,
                "user" => $user
            ]
        );
    }

    public function confirmation()
    {
        $port = $this->config->find(1)->getPort();
        $ship_limit = $this->config->find(1)->getShipLimit();
        $ordersTva = $this->config->selectLastThing('tva')->getTVA();
        $allLines = $this->orders_line->findByToken($_SESSION['token']);

        foreach ($allLines as $key => $value) {
            $priceHT += $value->beerPriceHT * $value->beerQTY;
        }

        if ($priceHT > $ship_limit) {
            $port = 0;
        }

        if(count($_POST) > 0) {
            if (empty($_POST['id'])) {
                die('Veuillez choisir une adresse');
            }
            
            $userInfos_id = $_POST['id'];
            $token = $_POST['token'];

            $fields = [
                "userInfos_id" => $userInfos_id, 
                "priceHT" => $priceHT, 
                "port" => $port, 
                "ordersTva" => $ordersTva, 
                "status_id" => 1, 
                "token" => $token];

                if ($this->orders->creating($fields)) {
                    $addArray = $this->user_infos->find($userInfos_id);
                    $address = $addArray->getAddress() . ", " . $addArray->getCity() . " " . $addArray->getZipCode() . " en " . $addArray->getCountry();
                    $price = $priceHT * $ordersTva;
                    $url = $this->generateUrl('my_order', ['id_user' => $_SESSION['auth']->getId(), 'id_order' => $this->orders->last()]);
                    $msg = ["html" => MailController::setMsgOrder($address, $price, $url)];
                    MailController::envoiMail("Confirmation de commande", $_SESSION['auth']->getMail(), $msg);
                }else{
                    $_SESSION['error'] = "Une erreure s'est produite !";
                    header('location: /profile');
                }
            

            $_SESSION['token'] = substr(md5(uniqid()), 0, 10);
            $_SESSION['panier'] = 0;
            header('location: /profile');
            exit();
        }
        $addresses = $this->user_infos->findUserId($_SESSION['auth']->getId());
        return $this->render('orders/confirm',[
            'lines' => $allLines, 
            'addresses' => $addresses,
            'priceHT' => $priceHT,
            'port' => $port,
            'tva' => $ordersTva
        ]);
    }

    public function show($user_id, $order_id)
    {
        $token_lines = $this->orders->find($order_id)->getToken();
        $port = $this->config->find(1)->getPort();
        $ship_limit = $this->config->find(1)->getShipLimit();
        $ordersTva = $this->config->selectLastThing('tva')->getTVA();
        $allLines = $this->orders_line->findByToken($token_lines);
        foreach ($allLines as $key => $value) {
            $priceHT += $value->beerPriceHT * $value->beerQTY;
        }
        if ($priceHT > $ship_limit) {
            $port = 0;
        }
        $status_id = $this->orders->selectLastThing('status_id')->getStatusId();
        $status = $this->status->selectStatus($status_id)->getStatus();
        return $this->render('orders/show', [
            'lines' => $allLines,
            'tva' => $ordersTva,
            'port' => $port,
            'priceHT' => $priceHT,
            'status' => $status
        ]);
    }
}
