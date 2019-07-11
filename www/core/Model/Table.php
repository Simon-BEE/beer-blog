<?php
namespace Core\Model;

use \Core\Controller\Database\DatabaseController;

class Table
{
    protected $db;

    protected $table;

    public function __construct(DatabaseController $db)
    {
        $this->db = $db;

        if (is_null($this->table)) {
            //App\Model\Table\ClassTable
            $parts = explode('\\', get_class($this));
            $class_name = end($parts);
            $this->table = strtolower(str_replace('Table', '', $class_name));
        }
    }

    public function count()
    {
        return $this->query("SELECT COUNT(id) as nbrow FROM {$this->table}", null, true, null);
    }

    public function lastId()
    {
        return $this->db->lastInsertId();
    }

    public function last()
    {
        return $this->query("SELECT MAX(id) as lastId FROM {$this->table}", null, true)->lastId;
    }

    public function selectLastThing($thing)
    {
        return $this->query("SELECT $thing FROM {$this->table} ORDER BY id DESC LIMIT 1", null, true);
    }

    public function find(int $id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id=?", [$id], true);
    }

    public function findBy(string $what, string $attributes, bool $one = false)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE $what = ?", [$attributes], $one);
    }

    public function query(string $statement, ?array $attributes = null, bool $one = false, ?string $class_name = null)
    {
        if (is_null($class_name)) {
            $class_name = str_replace('Table', 'Entity', get_class($this));
        }

        if ($attributes) {
            return $this->db->prepare(
                $statement,
                $attributes,
                $class_name,
                $one
            );
        } else {
            return $this->db->query(
                $statement,
                $class_name,
                $one
            );
        }
    }

    public function lastThird()
    {
        return $this->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 3", null, false, null);
    }

    public function latestById()
    {
        $id = $this->query("SELECT id FROM {$this->table} ORDER BY id DESC LIMIT 1", null, true, null)->getId();
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true, null);
    }

    public function allWithoutLimit()
    {
        return $this->query("SELECT * FROM {$this->table} ORDER BY id");
    }

    public function update(string $column, string $news, int $id)
    {
        return $this->db->query("UPDATE {$this->table} SET $column = '$news'  WHERE id = $id");
    }

    public function creating($fields){
        $sql_parts = [];// Création d'un tableau vide
        $attributes = [];// Création d'un tableau vide
        //On boucle sur le tableau associatif $fields
        foreach($fields as $k => $v){
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        //On colle les cases du tableau $sql_parts avec un ", "
        $sql_part = implode(', ', $sql_parts);
        
        //Appel de la methode query juste en dessous
        return $this->query("INSERT INTO {$this->table} SET $sql_part", $attributes, true);
    }

    public function updating($id, $column, $fields){
        $sql_parts = [];
        $attributes = [];
        foreach($fields as $k => $v){
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sql_part = implode(', ', $sql_parts);
        return $this->query("UPDATE {$this->table} SET $sql_part WHERE $column = ?", $attributes, true);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = $id");
    }
}
