<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:11 PM
 */

use shoppingCart\App;

session_start();

require __DIR__ .'/../vendor/autoload.php';
require __DIR__.'/../app/routes.php';

$app = new App;