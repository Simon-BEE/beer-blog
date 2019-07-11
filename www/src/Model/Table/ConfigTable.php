<?php
namespace App\Model\Table;

use Core\Model\Table;

class ConfigTable extends Table
{
    public function selectLastThing($thing)
    {
        return $this->query("SELECT $thing FROM config ORDER BY id DESC LIMIT 1", null, true);
    }
}