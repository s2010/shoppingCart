<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:41 PM
 */

$app->get('/',['shoppingCart\Controllers\HomeController', 'index'])->setName('home');

$app->get('/products/{slug}', ['shoppingCart\Controllers\ProductController', 'get'])->setName('product.get');
