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
}