<?php
namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class BeerEntity extends Entity
{
    private $id;

    private $name;

    private $slug;

    private $content;

    private $price;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the value of img
     */
    public function getImg()
    {
        dd($this->img);
        return $this->img;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function getExcerpt(int $lenght): string
    {
        return htmlentities(TextController::excerpt($this->getContent(), $lenght));
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
}
