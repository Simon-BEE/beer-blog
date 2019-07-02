<?php
namespace App\Model\Table;

use Core\Model\Table;
use App\Model\Entity\PostEntity;

class BeerTable extends Table
{
    public function allByLimit(int $limit, int $offset)
    {
        return $this->query("SELECT * FROM {$this->table} LIMIT {$limit}  OFFSET {$offset}");
    }

    public function all()
    {
        return $this->query("SELECT * FROM {$this->table}", null, false, null);
    }

    public function insertBeer($name, $slug, $img, $content, $price)
    {
        $sql = "INSERT INTO `beer` 
        (`name`, `slug`, `img`, `content`, `price`) 
        VALUES ( :name, :slug, :img, :content, :price)";
        $attributes = [
            ":name"         => $name,
            ":slug"         => $slug,
            ":img"          => $img,
            ":content"      => $content,
            ":price"        => $price
        ];
        return $this->query($sql, $attributes);
    }
}
