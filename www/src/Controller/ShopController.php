<?php
namespace App\Controller;

use \Core\Controller\Controller;
use \Core\Model\Entity;
use \App\Model\Entity\User_infosEntity;

class ShopController extends Controller
{
    public function __construct() {
        $this->loadModel('beer');
        $this->loadModel('user');
        $this->loadModel('user_infos');
        $this->loadModel('orders_line');
        $this->loadModel('orders');
        $this->loadModel('status');
        $this->loadModel('config');
    }

    public function purchaseOrder() {
        $allLines = $this->orders_line->findByToken($_SESSION['token']);
        $addresses = $this->user_infos->findUserId($_SESSION['user']->getId());
        return $this->render('shop/bondecommande', [
            'addresses' => $addresses,
            'lines' => $allLines
        ]);
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

            $this->orders->create($fields);
            $_SESSION['token'] = substr(md5(uniqid()), 0, 10);
            $_SESSION['panier'] = 0;
            header('location: /user/profile');
            exit();
        }
        
        $addresses = $this->user_infos->findUserId($_SESSION['user']->getId());
        return $this->render('shop/confirmation', [
            'beers' => $beers, 
            'lines' => $allLines, 
            'addresses' => $addresses,
            'priceHT' => $priceHT,
            'port' => $port,
            'tva' => $ordersTva]);
    }

    public function choice()
    {
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $form = $this->user_infos->find($id);
        // if ($form->getUserId() !== $user_id) {
        //     die('Tentative de hack échouée! Try again.');
        // }
        $form = $form->objectToArray($form);
        
        if($form) {
            echo json_encode($form);
            die;
        }
        else {
            echo 'error';
        }
    }

    public function basket()
    {
        $requiredFields=['beer_id', 'beerQTY'];

        foreach($requiredFields as $key => $value) {
            $fields[$value] = htmlspecialchars($_POST[$value]);
        }
        if (empty($_SESSION['token'])) {
            $token = substr(md5(uniqid()), 0, 10);
            $_SESSION['token'] = $token;
        }
        $fields['token'] = $_SESSION['token'];
        $fields['user_id'] = $_SESSION['auth']->getId();
        $fields['beerPriceHT'] = $this->beer->find($_POST['beer_id'])->getPriceHT();
        
        if ($this->orders_line->findByBeerIdAndToken($_POST['beer_id'])) {
            $ok = $this->orders_line->updateLine($_POST['beer_id'], $fields);
        }else{
            $ok = $this->orders_line->creating($fields);
        }

        $quantities = $this->orders_line->selectAllQtyByToken($_SESSION['token']);
        foreach ($quantities as $key => $value) {
            $totalQty += $value->getBeerQTY();
        }
        $_SESSION['panier'] = $totalQty;

        if ($ok) {
            echo "ok";
            die;
        }else{
            echo "error";
            die;
        }
    }

    public function updateBasket()
    {
        $requiredFields=['beer_id', 'beerQTY'];

        foreach($requiredFields as $key => $value) {
            $fields[$value] = htmlspecialchars($_POST[$value]);
        }
        $fields['token'] = $_SESSION['token'];
        $fields['user_id'] = $_SESSION['auth']->getId();
        $fields['beerPriceHT'] = $this->beer->find($_POST['beer_id'])->getPriceHT();
        if ($this->orders_line->findByBeerId($_POST['beer_id']) && $this->orders_line->findByToken($fields['token'])) {
            $ok = $this->orders_line->updateLine($_POST['beer_id'], $fields);
        }

        $quantities = $this->orders_line->selectAllQtyByToken($_SESSION['token']);
        foreach ($quantities as $key => $value) {
            $totalQty += $value->getBeerQTY();
        }
        $_SESSION['panier'] = $totalQty;
        
        if ($ok) {
            echo "ok";
            die;
        }else{
            echo "error";
            die;
        }
    }

    public function deleteLine()
    {
        $ok = $this->orders_line->delete($_POST['id']);
        if ($ok) {
            echo 'ok';
            die();
        }else{
            echo 'error';
            die();
        }
    }
}

