<?php
namespace App\Controller;

use \Core\Controller\Controller;

class PostController extends Controller
{

    public function __construct()
    {
        $this->loadModel('post');
        $this->loadModel('category');
        $this->loadModel('comment');
        $this->loadModel('user');
    }

    public function all()
    {
        $paginatedQuery = new PaginatedQueryAppController(
            $this->post,
            $this->generateUrl('posts')
        );
        $postById = $paginatedQuery->getItems();
        $title = 'Tous les posts';
        return $this->render(
            'post/all',
            [
                "title" => $title,
                "posts" => $postById,
                "paginate" => $paginatedQuery->getNavHtml()
            ]
        );
    }

    public function show(string $slug, int $id)
    {
        $post = $this->post->find($id);
        if (!$post) {
            throw new \Exception('Aucun article ne correspond à cet ID');
        }
        if ($post->getSlug() !== $slug) {
            $url = $this->generateUrl('post', ['id' => $id, 'slug' => $post->getSlug()]);
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }

        $categories = $this->category->allInId($post->getId());
        $comments = $this->comment->allInId($post->getId());
        $title = "article : " . $post->getName();

        return $this->render(
            "post/show",
            [
                "title" => $title,
                "categories" => $categories,
                "post" => $post,
                "comments" => $comments,
                "user" => $_SESSION['auth']
            ]
        );
    }

    public function comment(string $slug, int $id)
    {
        if (empty($_POST)) {
            header('location: /posts');
        }
        
        if (isset($_POST['mail']) && !empty($_POST['mail']) &&
            isset($_POST['login']) && !empty($_POST['login']) &&
            isset($_POST['content']) && !empty($_POST['content']) &&
            isset($_POST['id']) && !empty($_POST['id'])) {
            $name = htmlspecialchars($_POST['login']);
            $content = htmlspecialchars($_POST['content']);
            $id_user = $_POST['id'];
            $verif = $this->user->exist($_POST["mail"]);

            if ($verif) {
                $this->comment->post($id, $id_user, $name, $content);
                $url = $this->generateUrl('post', ['id' => $id, 'slug' => $slug]);
                header('location: '.$url);
            } else {
                $_SESSION['error'] = 'Veuillez vous enregistrer';
                unset($_SESSION['error']);
            }
        } else {
            $_SESSION['error'] = 'Erreur';
            unset($_SESSION['error']);
        }
    }
}
