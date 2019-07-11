<?php
namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class BeerEntity extends Entity
{
    private $id;
    private $title;
    private $slug;
    private $img;
    private $content;
    private $priceHT;
    private $stock;

    public function getId() {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getSlug(): string {
        return $this->slug;
    }

    public function getImg(): string {
        return $this->img;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getPriceHT(): float {
        return $this->priceHT;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function getUrl(): string
    {
        return \App\App::getInstance()
            ->getRouter()
            ->url('beer', [
                "slug" => $this->getSlug(),
                "id" => $this->getId()
            ]);
    }

    public function getAdminUrl():string
    {
        return \App\App::getInstance()->getRouter()->url("admin_beer_edit", [
            "slug" => $this->getSlug(),
            "id" => $this->getId()
        ]);
    }

    public function deleteUrl():string
    {
        return \App\App::getInstance()->getRouter()->url("admin_beer_delete", [
            "slug" => $this->getSlug(),
            "id" => $this->getId()
        ]);
    }
}
