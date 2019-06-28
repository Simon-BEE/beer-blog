<?php
namespace App\Model\Entity;

use \Core\Model\Entity;

class OrdersEntity extends Entity
{
    private $id;

    private $id_user;

    private $ids_product;

    public $priceTTC;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of id_user
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * Get the value of ids_product
     */
    public function getProducts()
    {
        return unserialize($this->ids_product);
    }

    /**
     * Get the value of priceTTC
     */
    public function getPriceTTC()
    {
        return $this->priceTTC;
    }

    public function getUrl()
    {
        return \App\App::getInstance()
            ->getRouter()
            ->url('orders', [
                "id" => $this->getId(),
                "id_user" => $this->getIdUser()
            ]);
    }

    public function getAdminUrl():string
    {
        return \App\App::getInstance()->getRouter()->url("admin_order_edit", [
            "id" => $this->getId(),
            "id_user" => $this->getIdUser()
        ]);
    }
}