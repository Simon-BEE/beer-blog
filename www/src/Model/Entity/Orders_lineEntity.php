<?php
namespace App\Model\Entity;

use Core\Model\Entity;

class Orders_lineEntity extends Entity
{

    public $id;
    public $user_id;
    public $beer_id;
    public $beerPriceHT;
    public $beerQTY;
    public $token;

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getBeerId()
    {
        return $this->beer_id;
    }
    
    public function getBeerPriceHT()
    {
        return $this->beerPriceHT;
    }

    public function getBeerQTY()
    {
        return $this->beerQTY;
    }

    public function getToken()
    {
        return $this->token;
    }
}
