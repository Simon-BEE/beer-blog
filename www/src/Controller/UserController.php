<?php
namespace App\Controller;

use \Core\Controller\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->loadModel('user');
        $this->loadModel('orders');
    }

    public function register()
    {
        if (!empty($_SESSION['auth'])) {
            $title = "Profile";
            $this->render(
                'user/profile',
                [
                    "title" => $title,
                    "user" => $_SESSION['auth']
                ]
            );
        }
        
        if (empty($_POST)) {
            $title = "Enregistrement";
            $this->render(
                'user/register',
                [
                    "title" => $title,
                ]
            );
        }

        if(	isset($_POST["lastname"]) && !empty($_POST["lastname"]) &&
		isset($_POST["firstname"]) && !empty($_POST["firstname"]) &&
		isset($_POST["address"]) && !empty($_POST["address"]) &&
		isset($_POST["zipCode"]) && !empty($_POST["zipCode"]) &&
		isset($_POST["city"]) && !empty($_POST["city"]) &&
		isset($_POST["country"]) && !empty($_POST["country"]) &&
		isset($_POST["phone"]) && !empty($_POST["phone"]) &&
		isset($_POST["mail"]) && !empty($_POST["mail"]) &&
		isset($_POST["mailVerify"]) && !empty($_POST["mailVerify"]) &&
		isset($_POST["password"]) && !empty($_POST["password"]) &&
		isset($_POST["passwordVerify"]) && !empty($_POST["passwordVerify"])	){
            if(
                (filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL) && 
                    $_POST["mail"] == $_POST["mailVerify"]) &&
                ($_POST["password"] == $_POST["passwordVerify"])
            ){
                $user = $this->user->exist($_POST["mail"]);
                if (!$user) {
                    $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);
                    $this->user->register(
                        $_POST["lastname"], $_POST["firstname"], $_POST["address"], $_POST["zipCode"],
                        $_POST["city"], $_POST["country"], $_POST["phone"], $_POST["mail"], $password);
                        //rediriger sur page profil
                        header('location: /connect');
                }else{
                    die('Adresse mail déjà enregistré');
                }
            }else{
                die('Adresse mail ou mot de passe non valide');
            }
        }
    }

    public function connect()
    {
        if (!empty($_SESSION['auth'])) {
            //header location
            $title = "Profile";
            $this->render(
                'user/profile',
                [
                    "title" => $title,
                    "user" => $_SESSION['auth']
                ]
            );
        }

        if (empty($_POST)) {
            $title = "Connexion";
            $this->render(
                'user/connect',
                [
                    "title" => $title,
                ]
            );
        }

        if (isset($_POST["mail"]) && !empty($_POST["mail"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $user = $this->user->exist($_POST["mail"]);
            if ($user && password_verify($_POST["password"], $user->getPassword())) {
                $auth = $this->connected($user);
                //rediriger sur page profil
                $title = "Bienvenue, ". $auth->getFirstname() ."!";
                $this->render(
                    'user/profile',
                    [
                        "title" => $title,
                        "user" => $auth
                    ]
                );
            }else{
                die('Erreur d\'identification');
            }
        }
    }

    public function profile()
    {
        if(empty($_SESSION['auth'])){
            $title = "Connexion";
            $this->render(
                'user/connect',
                [
                    "title" => $title
                ]
            );
            exit();
        }
        $auth = $_SESSION['auth'];
        $orders = $this->orders->allById($auth->getId());
        dump($orders);
        $title = "Profile";
        $this->render(
            'user/profile',
            [
                "title" => $title,
                "user" => $auth,
                "orders" => $orders
            ]
        );
        

    }
}