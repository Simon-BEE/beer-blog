<?php
$basePath = dirname(__dir__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';

$app = App\App::getInstance();
$app->setStartTime();
$app::load();

$app->getRouter($basePath)
    ->get('/', 'Site#index', 'home')
    ->get('/order', 'orders#order', 'order')
    ->get('/orders/[i:id]-[i:id_user]', 'orders#confirm', 'orders')
    ->post('/order', 'orders#order', 'purchase')
    ->get('/register', 'user#register', 'register')
    ->post('/register', 'user#register', 'register2')
    ->get('/check/[*:token]-[i:id_user]', 'user#checking', 'checking')
    ->get('/connect', 'user#connect', 'connect')
    ->post('/connect', 'user#connect', 'connect2')
    ->get('/profile', 'user#profile', 'profile')
    ->post('/profile', 'user#profile', 'profile2')
    ->get('/posts', 'post#all', 'posts')
    ->get('/post/[*:slug]-[i:id]', 'post#show', 'post')
    ->get('/beers', 'beer#all', 'beers')
    ->get('/beer/[*:slug]-[i:id]', 'beer#show', 'beer')
    ->get('/categories', 'category#all', 'categories')
    ->get('/category/[*:slug]-[i:id]', 'category#show', 'category')
    ->run();
