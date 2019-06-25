<?php
namespace App\Model\Table;

use \Core\Model\Table;

class OrdersTable extends Table
{

    public function purchase($beerArray, $quantity, $id_user)
    {
        $beerTotal = [];
        foreach ($beerArray as $key => $beer) {
            $beerTotal[$beer->id]= $beer;
        }
        $priceTTC = 0;
        foreach($quantity as $key => $valueQty) { //on boucle sur le tableau $_POST["qty"]
            if($valueQty > 0) {
                $price = $beerTotal[$key]->price; 
                $name = $beerTotal[$key]->name;
                $qty[$key] = ['name' => $name, 'qty' => $valueQty, "price"=>$price];
                $priceTTC += $valueQty * $price * 1.2;
            }
            
        }
        $serialCommande = serialize($qty); //On convertit le tableau $qty en String pour 												l'envoyer en bdd plus tard.
        $attributes = [":id_user"=>$id_user, ":ids_product"=>$serialCommande, ":priceTTC"=>$priceTTC];
        $statement = "INSERT INTO `orders` (`id_user`,`ids_product`,`priceTTC`) VALUES (:id_user, :ids_product, :priceTTC)";
        $this->query($statement, $attributes);
    }

    public function oneById($id)
    {
        # code...
    }

    public function allById($id)
    {
        return $this->query("SELECT * FROM orders WHERE id_user = ?", [$id]);
    }
}