<?php
namespace App\Model\Table;

use \Core\Model\Table;

class OrdersTable extends Table
{
    public function getOrders($id)
    {
        return $this->query(
            "SELECT *, orders.token as t, orders.id as i FROM orders 
            JOIN user_infos ON user_infos.id = orders.userInfos_id
            JOIN user ON user.id = user_infos.user_id
            WHERE user.id = $id");
    }
    
}
