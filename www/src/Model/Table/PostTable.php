<?php
namespace App\Model\Table;

use Core\Model\Table;
use App\Model\Entity\PostEntity;

class PostTable extends Table
{

    public function countById(?int $id = null)
    {
        if ($id) {
            return $this->query("SELECT COUNT(*) as nbrow FROM {$this->table} as p
            JOIN post_category as pc ON pc.post_id = p.id
            WHERE pc.category_id = {$id}", null, true);
        } else {
            return $this->query("SELECT COUNT(id) as nbrow FROM {$this->table}", null, true, null);
        }
    }

    public function allByLimit(int $limit, int $offset)
    {
        $posts = $this->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT {$limit}  OFFSET {$offset}");

        $ids = array_map(function (PostEntity $post) {
            return $post->getId();
        }, $posts);


        $categories = (new CategoryTable($this->db))->allInId(implode(', ', $ids));

        $postById = [];
        foreach ($posts as $post) {
            $postById[$post->getId()] = $post;
        }
        foreach ($categories as $category) {
            $postById[$category->post_id]->setCategories($category);
        }

        return $postById;
    }

    public function allInIdByLimit(int $limit, int $offset, int $id)
    {

        $posts = $this->query("SELECT * FROM {$this->table} as p
            JOIN post_category as pc ON pc.post_id = p.id
            WHERE pc.category_id = {$id}
            LIMIT {$limit}  OFFSET {$offset}");

        $ids = array_map(function (PostEntity $post) {
            return $post->getId();
        }, $posts);

        $categories = (new CategoryTable($this->db))->allInId(implode(', ', $ids));

        $postById = [];
        foreach ($posts as $post) {
            $postById[$post->getId()] = $post;
        }
        foreach ($categories as $category) {
            $postById[$category->post_id]->setCategories($category);
        }

        return $postById;
    }

    public function allInIdByThird(int $id)
    {

        $posts = $this->query("SELECT * FROM {$this->table} as p
            JOIN post_category as pc ON pc.post_id = p.id
            WHERE pc.category_id = {$id}
            LIMIT 3");

        $ids = array_map(function (PostEntity $post) {
            return $post->getId();
        }, $posts);

        $categories = (new CategoryTable($this->db))->allInId(implode(', ', $ids));

        $postById = [];
        foreach ($posts as $post) {
            $postById[$post->getId()] = $post;
        }
        foreach ($categories as $category) {
            $postById[$category->post_id]->setCategories($category);
        }

        return $postById;
    }

    public function insertPost($name, $slug, $content)
    {
        $createdAt = date("Y-m-d h:i:s");
        $sql = "INSERT INTO `post` 
        (`name`, `slug`, `content`, `created_at`) 
        VALUES ( :name, :slug, :content, :created_at)";
        $attributes = [
            ":name"         => $name,
            ":slug"         => $slug,
            ":content"      => $content,
            ":created_at"    => $createdAt
        ];
        return $this->query($sql, $attributes);
    }
}
