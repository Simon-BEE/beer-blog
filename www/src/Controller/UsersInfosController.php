<?php
namespace App\Controller;

use \Core\Controller\Controller;

class UsersInfosController extends Controller
{
    public function __construct() {
        $this->loadModel('user_infos');
    }

    public function updateUserInfos($user_id, $id) {
        if ($_SESSION['auth']->getId() !== $user_id) {
            header('location: /user/profile');
            exit();
        }
        $add = $this->user_infos->find($id);

        if (!$add) {
            throw new \Exception('Aucune adresse ne correspond à cet ID');
        }
        if ($add->getUser_id() !== $user_id) {
            http_response_code(301);
            header('Location: /user/profile');
            exit();
        }

        if(count($_POST) > 0) {
            $requiredFields=['lastname', 'firstname', 'address', 'zipCode', 'city', 'country', 'phone'];
            foreach($requiredFields as $key => $value) {
                if(!$_POST[$value]) {
                    header('location: /user/profile');
                    exit();
                }
                $fields[$value] = htmlspecialchars($_POST[$value]);
            }
            //Mise à jours bdd grace à methode update de /core/Table.php
            $this->user_infos->updating($id, 'id', $fields);
            $_SESSION['success'] = "Votre adresse est bien mise à jour !";
        }
        $address = $this->user_infos->find($id);
        $this->render('user/address', ["address" => $address]);
    }

    public function createUserInfos()
    {
        $auth = $_SESSION['auth'];
        if (!empty($_POST["lastname"]) && !empty($_POST["firstname"]) && 
        !empty($_POST["address"]) && !empty($_POST["zipCode"]) && 
        !empty($_POST["city"]) && !empty($_POST["country"]) &&
        !empty($_POST["phone"])) {
            if ($_POST['user_id'] === $auth->getId()) {
                $requiredFields=['lastname', 'firstname', 'address', 'zipCode', 'city', 'country', 'phone', 'user_id'];
                foreach($requiredFields as $key => $value) {
                    $fields[$value] = htmlspecialchars($_POST[$value]);
                }
                $this->user_infos->creating($fields);
                $_SESSION['success'] = 'Vous avez bien ajouté une nouvelle adresse !';
                header('location: /profile');
                exit();
            } else {
                $_SESSION['error'] = 'Tentative de piratage échouée';
                unset($_SESSION['auth']);
                $this->render('user/connect', ["title" => $title]);
                unset($_SESSION['error']);
                return;
            }
        }
        
    }

    public function deleteUserInfos($user_id, $id) {
        if ($_SESSION['auth']->getId() !== $user_id) {
            header('location: /profile');
            exit();
        }
        $this->user_infos->delete($id);
        $_SESSION['success'] = "Vous avez supprimé une adresse";
        header('location: /profile');
        exit();
    }
}