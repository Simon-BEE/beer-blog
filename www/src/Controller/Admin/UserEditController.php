<?php
namespace App\Controller\Admin;

use \Core\Controller\Controller;

class UserEditController extends Controller
{
    public function __construct()
    {
        $this->loadModel('user');
    }

    public function userEdit($firstname, $id)
    {
        $user = $this->user->findBy("id", $id, true);
        if (!$user) {
            throw new \Exception('Aucun article ne correspond à cet ID');
        }
        if ($user->getFirstname() !== $firstname) {
            $url = $this->generateUrl('admin_user_edit', ['firstname' => $user->getFirstname(), 'id' => $id]);
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }
        
        $title = $user->getMail();
        
        return $this->render("admin/user/userEdit", [
            "title" => $title,
            "user" => $user
        ]);
    }

    public function userUpdate($firstname, $id)
    {
        $url = $this->generateUrl('admin_user_edit', ['firstname' => $firstname, 'id' => $id]);
        if (isset($_POST)) {
            $user_id = $_POST['user_id'];
            if (!empty($_POST['user_mail'])) {
                $mail = $_POST['user_mail'];
                $this->user->update('mail', $mail, $user_id);
                header('location: '.$url);
            }
            if (isset($_POST['user_token'])) {
                $token = $_POST['user_token'];
                $this->post->update("token", $token, $user_id);
                header('location: '.$url);
            }
        }
    }

    public function userDelete($firstname, $id)
    {
        $this->user->delete($id);
        header('location: /admin/users');
    }
}
