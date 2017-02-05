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
use Braintree_Configuration;

session_start();

require __DIR__ .'/../vendor/autoload.php';
require __DIR__.'/../app/routes.php';

$app = new App;
$container = $app->getContainer();

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

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('9hd36fd3d3g6njkx');
Braintree_Configuration::publicKey('kk8s37jr6byfw93h');
Braintree_Configuration::privateKey('11ab389f9f345f49961ea38965913023');

$app->add(new \shoppingCart\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \shoppingCart\Middleware\OldInputMiddleware($container->get(Twig::class)));
