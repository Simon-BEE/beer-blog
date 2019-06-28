<?php
namespace App\Controller;

use \Core\Controller\Controller;
use Core\Controller\Helpers\MailController;

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
            header('location: /profile');
        }
        
        if (empty($_POST)) {
            $title = "Enregistrement";
            $this->render('user/register', ["title" => $title]);
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
                    $token = substr(md5(uniqid()), 10, 20);
                    $this->user->register(
                        $_POST["lastname"], $_POST["firstname"], $_POST["address"], $_POST["zipCode"],
                        $_POST["city"], $_POST["country"], $_POST["phone"], $_POST["mail"], $password, $token);
        
                    $id_user = $this->user->exist($_POST["mail"])->getId();
                    $url = $this->generateUrl('checking', ['token' => $token, 'id_user' => $id_user]);
                    $msg = ["html" => "Veuillez cliquer sur le lien pour confirmer votre compte!<br/>
                            <a href='http://localhost".$url."'>http://localhost".$url."</a>"];
                    MailController::envoiMail("Confirmation compte", $_POST["mail"], $msg);
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

    public function checking($token, $id)
    {
        $title = "Verification d\'email";
        $token_check = $this->user->findBy("token", $token);
        $id_check = $this->user->findBy("id_user", $id);

        if($token_check[0] == $id_check[0]){
            $this->user->deleteToken($id);
            $check = 'ok';
            $this->render('user/checking', ["title" => $title, "check" => $check]);
        }else{
            $this->render(
                'user/checking',
                [
                    "title" => $title
                ]);
        }
    }

    public function connect()
    {
        if (!empty($_SESSION['auth'])) {
            header('location: /profile');
            exit();
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
            if ($user && password_verify($_POST["password"], $user->getPwd())) {
                if ($user->getToken() === "") {
                    $auth = $this->connected($user);
                    header('location: /profile');
                    exit();
                }elseif ($user->getToken() === "CHMOD777") {
                    $auth = $this->connected($user);
                    header('location: /admin');
                    exit();
                }else{
                    die('Veuillez vérifier vos email, afin de valider votre inscription.');
                }
            }else{
                die('Erreur d\'identification');
            }
        }
    }

    public function profile()
    {
        if(empty($_SESSION['auth'])){
            header("location: /connect");
            exit();
        }

        $auth = $_SESSION['auth'];

        if (isset($_POST["lastname"]) && !empty($_POST["lastname"]) &&
		isset($_POST["firstname"]) && !empty($_POST["firstname"]) &&
		isset($_POST["address"]) && !empty($_POST["address"]) &&
		isset($_POST["zipCode"]) && !empty($_POST["zipCode"]) &&
		isset($_POST["city"]) && !empty($_POST["city"]) &&
		isset($_POST["country"]) && !empty($_POST["country"]) &&
		isset($_POST["phone"]) && !empty($_POST["phone"])) {
            if ((int)$_POST['id'] === $auth->getId()) {
                $this->user->updateInfo(
                    $_POST["lastname"], $_POST["firstname"], $_POST["address"], $_POST["zipCode"],
                    $_POST["city"], $_POST["country"], $_POST["phone"], $_POST['id']
                );
                header('location: /profile');
            }else{
                die('coquin ;)');
            }
        }

        if (isset($_POST["passwordOld"]) && !empty($_POST["passwordOld"]) && 
            isset($_POST["password"]) && !empty($_POST["password"]) &&
            isset($_POST["passwordVerify"]) && !empty($_POST["passwordVerify"])) {
            if ($_POST["password"] === $_POST["passwordVerify"]) {
                if (password_verify($_POST["passwordOld"], $auth->getPwd()) && (int)$_POST['id'] === $auth->getId()) {
                    $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);
                    $this->user->updatePwd($password, $_POST['id']);
                    header('location: /profile');
                }else{
                    die('erreur');
                }
            }else{
                die('password non identique');
            }
        }
        $user = $this->user->exist($auth->getMail());
        $orders = $this->orders->allById($auth->getId());
        $title = "Profile";
        $this->render(
            'user/profile',
            [
                "title" => $title,
                "user" => $user,
                "orders" => $orders
            ]
        );
    }

    public function logout()
    {
        if (!empty($_SESSION['auth'])) {
            unset($_SESSION['auth']);
        }
        header('location: /');
    }
}