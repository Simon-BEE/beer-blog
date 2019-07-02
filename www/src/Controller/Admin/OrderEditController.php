<?php
namespace App\Controller\Admin;

use \Core\Controller\Controller;

class OrderEditController extends Controller
{
    public function __construct()
    {
        $this->loadModel('orders');
    }

    public function orderEdit($id, $id_user)
    {
        $order = $this->orders->find($id);
        if (!$order) {
            throw new \Exception('Aucun article ne correspond à cet ID');
        }
        if ($order->getIdUser() !== $id_user) {
            $url = $this->generateUrl('admin_order_edit', ['id' => $id, 'id_user' => $order->getIdUser()]);
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }
        $products = $order->getProducts();
        $title = "Commande n°".$order->getId();
        
        return $this->render("admin/order/orderEdit", [
            "title" => $title,
            "order" => $order,
            "products" => $products
        ]);
    }

    public function orderUpdate($slug, $id)
    {
        $order = $this->order->find($id);
        $url = $this->generateUrl('admin_order_edit', ['slug' => $order->getSlug(), 'id' => $id]);
        if (isset($_POST)) {
            if (!empty($_POST['order_name']) && $id === $_POST['order_id']) {
                $this->order->update('name', $_POST['order_name'], $id);
                header('Location: ' . $url);
            }

            if (!empty($_POST['order_slug']) && $id === $_POST['order_id']) {
                if (preg_match("#^[a-zA-Z0-9_-]*$#", $_POST['order_slug'])) {
                    $this->order->update('slug', $_POST['order_slug'], $id);
                    header('Location: ' . $url);
                } else {
                    dd('error');
                }
            }

            if (!empty($_POST['order_img']) && $id === $_POST['order_id']) {
                $this->order->update('img', $_POST['order_img'], $id);
                header('Location: ' . $url);
            }

            if (!empty($_POST['order_content']) && $id === $_POST['order_id']) {
                $this->order->update('content', $_POST['order_content'], $id);
                header('Location: ' . $url);
            }
        }
    }

    public function orderDelete($id, $id_user)
    {
        $this->orders->delete($id);
        header('location: /admin/orders');
    }
}
