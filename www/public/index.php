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
    ->get('/logout', 'user#logout', 'logout')
    ->get('/posts', 'post#all', 'posts')
    ->get('/post/[*:slug]-[i:id]', 'post#show', 'post')
    ->post('/post/[*:slug]-[i:id]', 'post#comment', 'comment')
    ->get('/beers', 'beer#all', 'beers')
    ->get('/beer/[*:slug]-[i:id]', 'beer#show', 'beer')
    ->get('/categories', 'category#all', 'categories')
    ->get('/category/[*:slug]-[i:id]', 'category#show', 'category')
    ->get('/admin', 'admin\Admin#index', 'admin')
    ->get('/admin/posts', 'admin\Admin#posts', 'admin_posts')
    ->get('/admin/beers', 'admin\Admin#beers', 'admin_beers')
    ->get('/admin/orders', 'admin\Admin#orders', 'admin_orders')
    ->get('/admin/categories', 'admin\Admin#categories', 'admin_categories')
    ->get('/admin/users', 'admin\Admin#users', 'admin_users')
    ->get('/admin/posts/[*:slug]-[i:id]', 'admin\PostEdit#PostEdit', 'admin_posts_edit')
    ->post('/admin/postUpdate', 'admin\PostEdit#postUpdate', 'admin_post_update')
    ->post('/admin/postInsert', 'admin\PostEdit#postInsert', 'admin_post_insert')
    ->get('/admin/category/[*:slug]-[i:id]', 'admin\CategoryEdit#categoryEdit', 'admin_category_edit')
    ->post('/admin/categoryUpdate', 'admin\CategoryEdit#categoryUpdate', 'admin_category_update')
    ->get('/admin/user/[*:lastname]-[i:id]', 'admin\UserEdit#userEdit', 'admin_user_edit')
    ->get('/admin/beer/[*:slug]-[i:id]', 'admin\BeerEdit#beerEdit', 'admin_beer_edit')
    ->run();
    