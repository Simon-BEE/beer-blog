<?php
namespace App\Model\Table;

use Core\Model\Table;

class User_infosTable extends Table
{
    public function findUserId($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE user_id=?", [$id]);
    }
}