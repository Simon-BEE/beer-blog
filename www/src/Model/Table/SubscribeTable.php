<?php
namespace App\Model\Table;

use Core\Model\Table;

class SubscribeTable extends Table
{
    public function subscribe($email)
    {
        return $this->query("INSERT INTO subscribe (email) VALUE ('$email')");
    }
}
