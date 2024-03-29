<?php
namespace App\Model\Table;

use Core\Model\Table;

class CategoryTable extends Table
{

    public function allInId(string $ids)
    {
        return $this->query("SELECT c.*, pc.post_id
                FROM post_category pc 
                LEFT JOIN category c on pc.category_id = c.id
                WHERE post_id IN (" . $ids . ")");
    }

    public function allByLimit(int $limit, int $offset)
    {
        return $this->query("SELECT * FROM {$this->table} LIMIT {$limit}  OFFSET {$offset}");
    }

    public function lastThirdItems($ids)
    {
        return $this->query("SELECT *
                            FROM post_category 
                            LEFT JOIN post on post_category.post_id = post.id
                            WHERE category_id IN (" . $ids . ") ORDER BY id DESC LIMIT 3");
    }

    public function insertCategory($name, $slug)
    {
        $sql = "INSERT INTO `category` 
        (`name`, `slug`) 
        VALUES ( :name, :slug)";
        $attributes = [
            ":name"         => $name,
            ":slug"         => $slug
        ];
        return $this->query($sql, $attributes);
    }
}
