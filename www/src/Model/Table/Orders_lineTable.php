<?php
namespace App\Model\Table;

use Core\Model\Table;

class Orders_lineTable extends Table
{
    public function findByUserId($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE user_id=?", [$id]);
    }

    public function findByToken($token)
    {
        return $this->query("SELECT *, orders_line.id as line_id FROM {$this->table} 
        JOIN beer ON {$this->table}.beer_id = beer.id
        WHERE token=?", [$token]);
    }

    public function findByBeerId($beerId)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE beer_id=?", [$beerId]);
    }

    public function findByBeerIdAndToken($beerId)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE beer_id=? AND token=?", [$beerId, $_SESSION['token']]);
    }

    public function updateLine(int $id, $fields){
        $sql_parts = [];
        $attributes = [];
        foreach($fields as $k => $v){
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $token = $_SESSION['token'];
        $sql_part = implode(', ', $sql_parts);
        
        return $this->query("UPDATE {$this->table} SET $sql_part WHERE `beer_id` = '$id' AND `token` = '$token'", $attributes, true);
    }

    public function selectAllQtyByToken($token)
    {
    return $this->query("SELECT beerQTY FROM {$this->table} WHERE token = '$token'");
    }
}
