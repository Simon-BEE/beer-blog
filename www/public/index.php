<?php
$basePath = dirname(__dir__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';

$app = App\App::getInstance();
$app->setStartTime();
$app::load();

$app->getRouter($basePath)
    ->get('/', 'Site#index', 'home')
    ->get('/order', 'Site#order', 'order')
    ->get('/register', 'user#register', 'register')
    ->post('/register', 'user#register', 'register2')
    ->get('/connect', 'user#connect', 'connect')
    ->post('/connect', 'user#connect', 'connect2')
    ->get('/posts', 'post#all', 'posts')
    ->get('/post/[*:slug]-[i:id]', 'post#show', 'post')
    ->get('/beers', 'beer#all', 'beers')
    ->get('/beer/[*:slug]-[i:id]', 'beer#show', 'beer')
    ->get('/categories', 'category#all', 'categories')
    ->get('/category/[*:slug]-[i:id]', 'category#show', 'category')
    ->run();
