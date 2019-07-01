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
    }

    public function index()
    {
        $lastBeers = $this->beer->lastThird();
        $lastPosts = $this->post->lastThird();
        $title = 'HOME';
        $this->render(
            'site/index',
            [
                "title" => $title,
                "posts" => $lastPosts,
                "beers" => $lastBeers
            ]
        );
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
        $this->render(
            'site/contact',
            [
                "title" => $title
            ]
        );
    }
}
