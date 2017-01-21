<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:11 PM
 */

use shoppingCart\App;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;

session_start();

require __DIR__ .'/../vendor/autoload.php';
require __DIR__.'/../app/routes.php';

$app = new App;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'cart',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$app->add(new \shoppingCart\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \shoppingCart\Middleware\OldInputMiddleware($container->get(Twig::class)));
