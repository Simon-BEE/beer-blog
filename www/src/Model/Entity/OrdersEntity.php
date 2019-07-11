<?php
namespace App\Model\Entity;

use \Core\Model\Entity;

class OrdersEntity extends Entity
{
    private $id;
    private $userInfos_id;
    private $priceHT;
    private $port;
    private $ordersTva;
    private $created_at;
    private $status_id;
    private $token;

    public function getId()
    {
        return $this->id;
    }

    public function getUserInfosId()
    {
        return $this->userInfos_id;
    }

    public function getPriceHT()
    {
        return $this->priceHT;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getOrderTva()
    {
        return $this->ordersTva;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setUrlOrder()
    {
        return \App\App::getInstance()
            ->getRouter()
            ->url('order_address', [
                "token" => $this->getToken(),
                "id" => $this->getId()
            ]);
    }

    // public function getUrl()
    // {
    //     return \App\App::getInstance()
    //         ->getRouter()
    //         ->url('orders', [
    //             "id" => $this->getId(),
    //             "id_user" => $this->getIdUser()
    //         ]);
    // }

    // public function getAdminUrl():string
    // {
    //     return \App\App::getInstance()->getRouter()->url("admin_order_edit", [
    //         "id" => $this->getId(),
    //         "id_user" => $this->getIdUser()
    //     ]);
    // }

    // public function deleteUrl():string
    // {
    //     return \App\App::getInstance()->getRouter()->url("admin_order_delete", [
    //         "id" => $this->getId(),
    //         "id_user" => $this->getIdUser()
    //     ]);
    // }
}
