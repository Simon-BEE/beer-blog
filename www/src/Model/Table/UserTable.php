<?php
namespace App\Model\Table;

use Core\Model\Table;

class UserTable extends Table
{

    public function exist($mail)
    {
        return $this->query("SELECT * FROM user WHERE mail = ?", [$mail], true, null);
    }

    public function register( $lastname, $firstname, $address, $zipCode, $city, $country, $phone, $mail, $password )
    {
        $sql = "INSERT INTO `user` 
        (`lastname`, `firstname`, `address`, `zipCode`, `city`, `country`, `phone`, `mail`, `password`) 
        VALUES ( :lastname, :firstname, :address, :zipCode, :city, :country, :phone, :mail, :password)";
        $attributes = [
            ":lastname"		=> htmlspecialchars($lastname),
            ":firstname"	=> htmlspecialchars($firstname),
            ":address"		=> htmlspecialchars($address),
            ":zipCode"		=> htmlspecialchars($zipCode),
            ":city"			=> htmlspecialchars($city),
            ":country"		=> htmlspecialchars($country),
            ":phone"		=> htmlspecialchars($phone),
            ":mail"			=> htmlspecialchars($mail),
            ":password"		=> $password
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
                WHERE id_user = ?";
        return $this->query($sql,[$id]);
    }

    public function updatePwd($password, $id)
    {
        $sql = "UPDATE user SET password = '$password' WHERE id_user = ?";
        return $this->query($sql, [$id]);
    }
}