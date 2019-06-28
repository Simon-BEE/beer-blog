<?php
namespace App\Controller;

use \Core\Controller\Controller;

class BeerController extends Controller
{

    public function __construct()
    {
        $this->loadModel('beer');
    }

    public function all()
    {
        $paginatedQuery = new PaginatedQueryAppController(
            $this->beer,
            $this->generateUrl('beers')
        );

        $beers = $paginatedQuery->getItems();
        $user = $_SESSION['auth'];
        $title = 'Les bières';
        $this->render(
            'beer/all',
            [
                "title" => $title,
                "beers" => $beers,
                "user" => $user,
                "paginate" => $paginatedQuery->getNavHtml()
            ]
        );
    }

    public function show(string $slug, int $id)
    {

        $beer = $this->beer->find($id);

        if (!$beer) {
            throw new \Exception('Aucun article ne correspond à cet ID');
        }

        if ($beer->getSlug() !== $slug) {
            $url = $this->generateUrl('beer', ['id' => $id, 'slug' => $beer->getSlug()]);

            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }
        $user = $_SESSION['auth'];
        $title = "article : " . $beer->getName();

        $this->render(
            "beer/show",
            [
                "title" => $title,
                "beer" => $beer,
                "user" => $user
            ]
        );
    }
}