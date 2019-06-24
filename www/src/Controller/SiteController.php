<?php
namespace App\Controller;

use \Core\Controller\Controller;

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

    public function order()
    {
        if (!empty($_POST)) {
            $beers = $this->beer->all();
            $title = 'Commander';
            $this->render(
                'site/order',
                [
                    "title" => $title,
                    "beers" => $beers
                ]
            );
        }
        
    }
}