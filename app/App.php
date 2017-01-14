<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:14 PM
 */

namespace shoppingCart;

use Di\ContainerBuilder;
use DI\Bridge\Slim\App as DiBridge;

class App extends DiBridge
{
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            'settings.displayErrorDetails' => true ,
        ]);

        $builder->addDefinitions(__DIR__.'/container.php');
    }
}