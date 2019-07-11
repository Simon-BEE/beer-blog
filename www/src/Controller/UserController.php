<?php
namespace App\Controller;

use \Core\Controller\Controller;
use Core\Controller\Helpers\MailController;

class UserController extends Controller
{
    public function __construct()
    {
        $this->loadModel('user');
        $this->loadModel('user_infos');
        $this->loadModel('orders');
    }

    public function register()
    {
        if (!empty($_SESSION['auth'])) {
            header('location: /profile');
        }

        if (isset($_POST["mail"]) && !empty($_POST["mail"]) &&
        isset($_POST["mailVerify"]) && !empty($_POST["mailVerify"]) &&
        isset($_POST["password"]) && !empty($_POST["password"]) &&
        isset($_POST["passwordVerify"]) && !empty($_POST["passwordVerify"]) ) {
            if ((filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL) &&
                    $_POST["mail"] == $_POST["mailVerify"]) &&
                ($_POST["password"] == $_POST["passwordVerify"])
            ) {
                $user = $this->user->exist($_POST["mail"]);
                if (!$user) {
                    $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);
                    $token = substr(md5(uniqid()), 10, 20);
                    $fields['mail'] = htmlspecialchars($_POST['mail']);
                    $fields['password'] = $password;
                    $fields['token']= $token;
                    $this->user->creating($fields);

                    $id_user = $this->user->exist($_POST["mail"])->getId();
                    $url = $this->generateUrl('checking', ['token' => $token, 'id' => $id_user]);
                    $msgUrl = "http://localhost".$url;

                    $msg = ["html" => MailController::setMsgCheck($msgUrl, "Madame/Monsieur")];
                    MailController::envoiMail("Confirmation compte", $_POST["mail"], $msg);
                    //rediriger sur page profil
                    $_SESSION['success'] = 'Votre inscription s\'est bien déroulée, veuillez la confirmer en vous rendant sur votre boîte mail';
                    $this->render('user/connect', ["title" => "Connexion"]);
                    unset($_SESSION['success']);
                } else {
                    $_SESSION['error'] = 'Adresse mail déjà enregistré';
                    $this->render('user/register', ["title" => "Enregistrement"]);
                    unset($_SESSION['error']);
                    return;
                }
            } else {
                $_SESSION['error'] = 'Adresse mail ou mot de passe non valide';
                $this->render('user/register', ["title" => "Enregistrement"]);
                unset($_SESSION['error']);
                return;
            }
        }

        $this->render('user/register', ["title" => "Enregistrement"]);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    public function checking($token, $id)
    {
        $title = "Verification d\'email";
        $token_check = $this->user->findBy("token", $token);
        $id_check = $this->user->findBy("id", $id);

        if ($token_check[0] == $id_check[0]) {
            $this->user->deleteToken($id);
            $this->user->verify($id);
            $check = 'ok';
            return $this->render('user/checking', ["title" => $title, "check" => $check]);
        } else {
            return $this->render('user/checking', ["title" => $title]);
        }
    }

    public function connect()
    {
        if (!empty($_SESSION['auth'])) {
            header('location: /profile');
            exit();
        }

        if (isset($_POST["mail"]) && !empty($_POST["mail"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $user = $this->user->exist($_POST["mail"]);
            if ($user && password_verify($_POST["password"], $user->getPassword())) {
                if ($user->getToken() === "") {
                    $auth = $this->connected($user);
                    header('location: /profile');
                    exit();
                } elseif ($user->getToken() === "CHMOD777") {
                    $auth = $this->connected($user);
                    header('location: /admin');
                    exit();
                } else {
                    $_SESSION['error'] = 'Veuillez vérifier vos email, afin de valider votre inscription.';
                    $this->render('user/connect', ["title" => "Connexion"]);
                    unset($_SESSION['error']);
                    return;
                }
            } else {
                $_SESSION['error'] = 'Erreur d\'identification';
                $this->render('user/connect', ["title" => "Connexion"]);
                unset($_SESSION['error']);
                return;
            }
        }

        $this->render('user/connect', ["title" => "Connexion"]);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    public function profile()
    {
        if (empty($_SESSION['auth'])) {
            header("location: /connect");
            exit();
        }

        $auth = $_SESSION['auth'];

        if (isset($_POST["passwordOld"]) && !empty($_POST["passwordOld"]) &&
            isset($_POST["password"]) && !empty($_POST["password"]) &&
            isset($_POST["passwordVerify"]) && !empty($_POST["passwordVerify"])) {
            if ($_POST["password"] === $_POST["passwordVerify"]) {
                if (password_verify($_POST["passwordOld"], $auth->getPwd()) && (int)$_POST['id'] === $auth->getId()) {
                    $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);
                    $this->user->updatePwd($password, $_POST['id']);
                    $_SESSION['success'] = 'Mot de passe mis à jour';
                    header('location: /profile');
                    exit();
                } else {
                    $_SESSION['error'] = 'Ancien mot de passe incorrect';
                    header('location: /profile');
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Mots de passe non identique';
                header('location: /profile');
                exit();
            }
        }
        
        $orders = $this->orders->getOrders($auth->getId());
        $addresses = $this->user_infos->findUserId($_SESSION['auth']->getId());
        $this->render(
            'user/profile',
            [
                "title" => "Profile",
                "orders" => $orders,
                "addresses" => $addresses
            ]
        );
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    public function logout()
    {
        if (!empty($_SESSION['auth'])) {
            unset($_SESSION['auth']);
        }
        header('location: /');
    }
}
