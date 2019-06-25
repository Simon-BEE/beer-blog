<?php
namespace App\Model\Entity;

use Core\Model\Entity;

class UserEntity extends Entity
{

    private $id_user;

    private $lastname;

    private $firstname;

    private $address;

    private $zipCode;

    private $city;

    private $country;

    private $phone;

    private $mail;

    private $password;

    public function getId(): int
    {
        return $this->id_user;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getZipcode(): string
    {
        return $this->zipCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getPwd(): string
    {
        return $this->password;
    }

    public function getUrl(): string
    {
        return \App\App::getInstance()
            ->getRouter()
            ->url('category', [
                "lastname" => $this->getLastname(),
                "id" => $this->getId()
            ]);
    }
}