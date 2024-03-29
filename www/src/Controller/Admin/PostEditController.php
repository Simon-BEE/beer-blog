<?php
namespace App\Controller\Admin;

use \Core\Controller\Controller;
use \Core\Controller\PaginatedQueryController;

class PostEditController extends Controller
{
    public function __construct()
    {
        $this->loadModel('post');
        $this->loadModel('category');
        $this->loadModel('post_category');
    }
    public function postEdit($slug, $id)
    {
        $post = $this->post->find($id);
        if (!$post) {
            throw new \Exception('Aucun article ne correspond à cet ID');
        }
        if ($post->getSlug() !== $slug) {
            $url = $this->generateUrl('admin_posts_edit', ['id' => $id, 'slug' => $post->getSlug()]);
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }
        $categories = $this->category->allInId($post->getId());
        $allCategories = $this->category->allWithoutLimit();
        
        $title = $post->getName();
        
        return $this->render("admin/post/postsEdit", [
            "title" => $title,
            "categories" => $categories,
            "post" => $post,
            "allCategories" => $allCategories
        ]);
    }
    public function postUpdate($slug, $id)
    {
        $post = $this->post->find($id);
        $url = $this->generateUrl('admin_posts_edit', ['id' => $id, 'slug' => $post->getSlug()]);
        if (isset($_POST)) {
            $id = $_POST['post_id'];
            if (!empty($_POST['post_name'])) {
                $name = $_POST['post_name'];
                $this->post->update("name", $name, $id);
                header('location: '.$url);
            }
            if (!empty($_POST['post_slug'])) {
                (string)$slug = $_POST['post_slug'];
                if (preg_match("#^[a-zA-Z0-9_-]*$#", $slug)) {
                    $this->post->update("slug", $slug, $id);
                    header('location: '.$url);
                } else {
                    dd('error');
                }
            }
            if (!empty($_POST['post_content'])) {
                (string)$content = htmlspecialchars($_POST['post_content']);
                $this->post->update("content", $content, $id);
                header('location: '.$url);
            }
            /*
            if (!empty($_POST['select'])) {
                (string)$content = $_POST['select'];
                $this->post->update("content", $content, $id);
                header('location: /admin/posts');
            }
            */
        }
    }
    public function postInsert()
    {
        
        if (isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['slug']) && !empty($_POST['slug']) &&
            isset($_POST['content']) && !empty($_POST['content'])) {
            $slug = $this->post->findBy('slug', $_POST['slug'], true);
            if (!$slug) {
                if (preg_match("#^[a-zA-Z0-9_-]*$#", $_POST['slug'])) {
                    $this->post->insertPost($_POST['name'], $_POST['slug'], $_POST['content']);
                }
            } else {
                $_SESSION['error'] = 'slug déjà existant';
                $title = "Ajouter un article";
                $categories = $this->category->allWithoutLimit();
                return $this->render("admin/post/postInsert", ["title" => $title,"categories" => $categories]);
                unset($_SESSION['error']);
            }
            $categ = $this->category->allWithoutLimit();
            
            for ($i=1; $i <= count($categ); $i++) {
                if ($_POST[$i]) {
                    $post_id = $this->post->latestById()->getId();
                    $this->post_category->insertPC($post_id, $i);
                }
            }
        }
        $categories = $this->category->allWithoutLimit();
        $title = "Ajouter un article";
        
        return $this->render("admin/post/postInsert", [
            "title" => $title,
            "categories" => $categories
        ]);
    }

    public function postDelete($slug, $id)
    {
        $this->post->delete($id);
        header('location: /admin/posts');
    }
}
