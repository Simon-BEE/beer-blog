<?php
namespace App\Controller;

use \Core\Controller\Controller;
use Core\Controller\Helpers\MailController;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->loadModel('post');
        $this->loadModel('beer');
        $this->loadModel('subscribe');
    }

    public function index()
    {
        if (isset($_POST['subscribe']) && !empty($_POST['subscribe'])) {
            if (filter_var($_POST['subscribe'], FILTER_VALIDATE_EMAIL)) {
                $this->subscribe->subscribe($_POST['subscribe']);
                $_SESSION['success'] = "Email enregistré, vous recevrez la prochaine newsletter très bientôt";
                $lastBeers = $this->beer->lastThird();
                $lastPosts = $this->post->lastThird();
                return $this->render(
                    'site/index',
                    [
                        "title" => 'HOME',
                        "posts" => $lastPosts,
                        "beers" => $lastBeers
                    ]
                );
                unset($_SESSION['success']);
            } else {
                $_SESSION['error'] = "Format d'email non valide";
                $lastBeers = $this->beer->lastThird();
                $lastPosts = $this->post->lastThird();
                return $this->render(
                    'site/index',
                    [
                        "title" => 'HOME',
                        "posts" => $lastPosts,
                        "beers" => $lastBeers
                    ]
                );
                unset($_SESSION['error']);
            }
        }

        $lastBeers = $this->beer->lastThird();
        $lastPosts = $this->post->lastThird();
        return $this->render(
            'site/index',
            [
                "title" => 'HOME',
                "posts" => $lastPosts,
                "beers" => $lastBeers
            ]
        );
        unset($_SESSION['success']);
        unset($_SESSION['error']);
    }

    public function contact()
    {
        if (isset($_POST['mail']) && !empty($_POST['mail']) &&
            isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['subject']) && !empty($_POST['subject']) &&
            isset($_POST['content']) && !empty($_POST['content'])) {
            $mail = htmlspecialchars($_POST['mail']);
            $name = htmlspecialchars($_POST['name']);
            $subject = htmlspecialchars($_POST['subject']);
            $content = htmlspecialchars($_POST['content']);
            $msg = ["html" => $name." | email: ".$mail."| Vous a envoyé : <br />".$content];
            MailController::envoiMail($subject, "montluconaformac2019@gmail.com", $msg);
        }
        
        $title = 'Contact';
        return $this->render(
            'site/contact',
            [
                "title" => $title
            ]
        );
    }
}
