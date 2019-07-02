<?php
namespace App\Model\Entity;

use Core\Model\Entity;

class SubscribeEntity extends Entity
{

    private $id;
    private $email;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
