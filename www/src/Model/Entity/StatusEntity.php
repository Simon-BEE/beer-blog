<?php
namespace App\Model\Entity;

use Core\Model\Entity;


class StatusEntity extends Entity
{
    private $id;
    private $status;
    private $tva;
    private $port;
    private $ship_limit;

    public function getId() {
        return $this->id;
    }

    public function getStatus() {
        return $this->status;
    }
}
