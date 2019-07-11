<?php
namespace App\Model\Table;

use Core\Model\Table;

class StatusTable extends Table
{
    public function selectStatus($id)
    {
        return $this->query("SELECT status FROM status WHERE id = $id", null, true);
    }
}