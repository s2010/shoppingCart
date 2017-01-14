<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:28 PM
 */
use function DI\get;
use Interop\Container\ContainerInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

return [

   'router' => get(Slim\Route::class),
    
    Twig::class => function (ContainerInterface $c){

        $twig = new Twig(__DIR__.'/../resources/views',[
            'cache' => false
        ]);

        $twig->addExtention(new TwigExtension(
            $c->get('router'),
            $c->get('request')->getUrl()
        ));

        return $twig;
}
];