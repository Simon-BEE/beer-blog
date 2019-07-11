<?php
namespace App\Controller\Admin;

use Core\Controller\Controller;
use Core\Controller\PaginatedQueryController;

class CategoryEditController extends Controller
{
    public function __construct()
    {
        $this->loadModel('post');
        $this->loadModel('category');
    }
    public function categoryEdit($slug, $id)
    {
        $category = $this->category->find($id);
        if (!$category) {
            throw new \Exception('Aucune categorie ne correspond à cet ID');
        }
        if ($category->getSlug() !== $slug) {
            $url = $this->generateUrl('admin_category_edit', ['id' => $id, 'slug' => $category->getSlug()]);
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }
        
        $paginatedQuery = new PaginatedQueryController(
            $this->post,
            $this->generateUrl('admin_category_edit', ["id" => $category->getId(), "slug" => $category->getSlug()])
        );
        // if (!$paginatedQuery->getItemsInId($id)) {
        //     $postById = "";
        // }else{
        //     $postById = $paginatedQuery->getItemsInId($id);
        // }
        $postById = $paginatedQuery->getItemsInId($id);
        $title = $category->getName();
        
        return $this->render("admin/category/categoryEdit", [
            "title" => $title,
            "category" => $category,
            "posts" => $postById
        ]);
    }
    public function categoryUpdate($slug, $id)
    {
        $category = $this->category->find($id);
        $url = $this->generateUrl("admin_category_edit", ["id" => $category->getId(), "slug" => $category->getSlug()]);
        if (isset($_POST)) {
            $id = $_POST['cat_id'];
            ;
            if (!empty($_POST['cat_name'])) {
                (string)$name = $_POST['cat_name'];
                $this->category->update("name", $name, $id);
                header('location: '.$url);
            }
            if (!empty($_POST['cat_slug'])) {
                (string)$slug = $_POST['cat_slug'];
                if (preg_match("#^[a-zA-Z0-9_-]*$#", $slug)) {
                    $this->category->update("slug", $slug, $id);
                    header('location: '.$url);
                } else {
                    dd('error');
                }
            }
        }
    }

    public function categoryInsert()
    {
        if (isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['slug']) && !empty($_POST['slug'])) {
            $slug = $this->category->findBy('slug', $_POST['slug'], true);
            if (!$slug) {
                if (preg_match("#^[a-zA-Z0-9_-]*$#", $_POST['slug'])) {
                    $this->category->insertcategory($_POST['name'], $_POST['slug']);
                }
            } else {
                $_SESSION['error'] = 'slug déjà existant';
                return $this->render("admin/category/categoryInsert", ["title" => "Ajouter une catégorie"]);
                unset($_SESSION['error']);
            }
        }
        return $this->render("admin/category/categoryInsert", [
            "title" => "Ajouter une catégorie"
        ]);
    }

    public function categoryDelete($slug, $id)
    {
        $this->category->delete($id);
        header('location: /admin/categories');
    }
}
