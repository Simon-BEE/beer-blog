<?php
namespace App\Model\Entity;

use Core\Model\Entity;

class UserEntity extends Entity
{
    private $id;
    private $mail;
    private $password;
    private $token;
    private $createdAt;
    private $verify;

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return new \DateTime($this->createdAt);
    }

    public function getMail() {
        return $this->mail;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getVerify() {
        return $this->verify;
    }

    public function getToken() {
        return $this->token;
    }
    

    // public function getUrl(): string
    // {
    //     return \App\App::getInstance()
    //         ->getRouter()
    //         ->url('category', [
    //             "lastname" => $this->getLastname(),
    //             "id" => $this->getId()
    //         ]);
    // }

    // public function getAdminUrl():string
    // {
    //     return \App\App::getInstance()->getRouter()->url("admin_user_edit", [
    //         "lastname" => $this->getLastname(),
    //         "id" => $this->getId()
    //     ]);
    // }

    // public function deleteUrl():string
    // {
    //     return \App\App::getInstance()->getRouter()->url("admin_user_delete", [
    //         "lastname" => $this->getLastname(),
    //         "id" => $this->getId()
    //     ]);
    // }
}
