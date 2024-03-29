<?php
namespace App\Model\Table;

use Core\Model\Table;

class UserTable extends Table
{
    public function allWithoutLimit()
    {
        return $this->query("SELECT * FROM {$this->table} ORDER BY id");
    }

    public function find(int $id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id=?", [$id], true);
    }

    public function exist($mail)
    {
        return $this->query("SELECT * FROM user WHERE mail = ?", [$mail], true, null);
    }

    public function register($lastname, $firstname, $address, $zipCode, $city, $country, $phone, $mail, $password, $token)
    {
        $sql = "INSERT INTO `user` 
        (`lastname`, `firstname`, `address`, `zipCode`, `city`, `country`, `phone`, `mail`, `password`, `token`) 
        VALUES ( :lastname, :firstname, :address, :zipCode, :city, :country, :phone, :mail, :password, :token)";
        $attributes = [
            ":lastname"     => htmlspecialchars($lastname),
            ":firstname"    => htmlspecialchars($firstname),
            ":address"      => htmlspecialchars($address),
            ":zipCode"      => htmlspecialchars($zipCode),
            ":city"         => htmlspecialchars($city),
            ":country"      => htmlspecialchars($country),
            ":phone"        => htmlspecialchars($phone),
            ":mail"         => htmlspecialchars($mail),
            ":password"     => $password,
            ":token"        => $token
        ];
        return $this->query($sql, $attributes);
    }

    public function updateInfo($lastname, $firstname, $address, $zipCode, $city, $country, $phone, $id)
    {
        $sql = "UPDATE user SET 
                    lastname = '$lastname',
                    firstname = '$firstname',
                    address	= '$address',
                    zipCode	= '$zipCode',
                    city	= '$city',
                    country	= '$country',
                    phone   = '$phone'
                WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function updatePwd($password, $id)
    {
        return $this->query("UPDATE user SET password = '$password' WHERE id = ?", [$id]);
    }

    public function deleteToken($id)
    {
        return $this->query("UPDATE user SET token = '' WHERE id = ?", [$id]);
    }

    public function verify($id)
    {
        return $this->query("UPDATE user SET verify = 1 WHERE id = ?", [$id]);
    }

    public function latestById()
    {
        $id = $this->query("SELECT id FROM {$this->table} ORDER BY id DESC LIMIT 1", null, true, null)->getId();
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true, null);
    }

    public function update($column, $news, $id)
    {
        return $this->db->query("UPDATE {$this->table} SET $column = '$news'  WHERE id = '$id'");
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = $id");
    }
}
