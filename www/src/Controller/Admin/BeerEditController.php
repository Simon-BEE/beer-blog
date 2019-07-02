<?php
namespace App\Controller\Admin;

use \Core\Controller\Controller;

class BeerEditController extends Controller
{
    public function __construct()
    {
        $this->loadModel('beer');
    }

    public function beerEdit($slug, $id)
    {
        $beer = $this->beer->find($id);
        if (!$beer) {
            throw new \Exception('Aucun article ne correspond à cet ID');
        }
        if ($beer->getSlug() !== $slug) {
            $url = $this->generateUrl('admin_beer_edit', ['slug' => $beer->getSlug(), 'id' => $id]);
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }
        
        $title = $beer->getName();
        
        return $this->render("admin/beer/beerEdit", [
            "title" => $title,
            "beer" => $beer
        ]);
    }

    public function beerUpdate($slug, $id)
    {
        $beer = $this->beer->find($id);
        $url = $this->generateUrl('admin_beer_edit', ['slug' => $beer->getSlug(), 'id' => $id]);
        
        if (isset($_POST)) {
            if (!empty($_POST['beer_name']) && $id === $_POST['beer_id']) {
                $this->beer->update('name', $_POST['beer_name'], $id);
                header('Location: ' . $url);
            }

            if (!empty($_POST['beer_slug']) && $id === $_POST['beer_id']) {
                if (preg_match("#^[a-zA-Z0-9_-]*$#", $_POST['beer_slug'])) {
                    $this->beer->update('slug', $_POST['beer_slug'], $id);
                    header('Location: ' . $url);
                } else {
                    dd('error');
                }
            }

            if (!empty($_POST['beer_img']) && $id === $_POST['beer_id']) {
                $this->beer->update('img', $_POST['beer_img'], $id);
                header('Location: ' . $url);
            }

            if (!empty($_POST['beer_content']) && $id === $_POST['beer_id']) {
                $this->beer->update('content', $_POST['beer_content'], $id);
                header('Location: ' . $url);
            }

            if (!empty($_POST['beer_price']) && $id === $_POST['beer_id']) {
                $this->beer->update('price', $_POST['beer_price'], $id);
                header('Location: ' . $url);
            }
        }
    }

    public function beerInsert()
    {
        if (isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['slug']) && !empty($_POST['slug']) &&
            isset($_POST['img']) && !empty($_POST['img']) &&
            isset($_POST['content']) && !empty($_POST['content']) &&
            isset($_POST['price']) && !empty($_POST['price'])) {
            $price = (int)$_POST['price'];
            $slug = $this->beer->findBy('slug', $_POST['slug'], true);
            if (!$slug) {
                if (preg_match("#^[a-zA-Z0-9_-]*$#", $_POST['slug']) && is_int($price)) {
                    $this->beer->insertBeer($_POST['name'], $_POST['slug'], $_POST['img'], $_POST['content'], $price);
                }
            } else {
                $_SESSION['error'] = 'slug déjà existant';
                $title = "Ajouter une bière";
                return $this->render("admin/beer/beerInsert", ["title" => $title]);
                unset($_SESSION['error']);
            }
        }

        $title = "Ajouter une bière";
        
        return $this->render("admin/beer/beerInsert", [
            "title" => $title
        ]);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    public function beerDelete($slug, $id)
    {
        $this->beer->delete($id);
        header('location: /admin/beers');
    }
}
