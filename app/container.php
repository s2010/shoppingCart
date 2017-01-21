<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:28 PM
 */

use shoppingCart\Support\Storage\SessionStorage;
use function DI\get;
use Interop\Container\ContainerInterface;
use shoppingCart\Basket\Basket;
use shoppingCart\Models\Product;
use shoppingCart\Support\Storage\Contracts\StorageInterface;
use shoppingCart\Validation\Contracts\ValidatorInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

return [

   'router' => get(Slim\Route::class),
    
    ValidatorInterface::class => function (ContainerInterface $c) {
        return new Validator;
    },
    StorageInterface::class => function (ContainerInterface $c) {
        return new SessionStorage('cart');
    },

    Twig::class => function (ContainerInterface $c) {
        $twig = new Twig(__DIR__ . '/../resources/views', [
            'cache' => false
        ]);

        $twig->addExtension(new TwigExtension(
            $c->get('router'),
            $c->get('request')->getUri()
        ));

        $twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));

        return $twig;
    },

    Product::class => function (ContainerInterface $c) {
            return new Product;
    },
    Basket::class => function (ContainerInterface $c) {
        return new Basket(
            $c->get(SessionStorage::class),
            $c->get(Product::class)
        );
    }
];