<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:41 PM
 */

$app->get('/',['shoppingCart\Controllers\HomeController', 'index'])->setName('home');

$app->get('/products/{slug}', ['shoppingCart\Controllers\ProductController', 'get'])->setName('product.get');

$app->get('/cart', ['shoppingCart\Controllers\CartController', 'index'])->setName('cart.index');
$app->get('/cart/add/{slug}/{quantity}', ['shoppingCart\Controllers\CartController', 'add'])->setName('cart.add');
$app->post('/cart/update/{slug}', ['shoppingCart\Controllers\CartController', 'update'])->setName('cart.update');

$app->get('/order', ['shoppingCart\Controllers\OrderController', 'index'])->setName('order.index');
$app->get('/order/{hash}', ['shoppingCart\Controllers\OrderController', 'show'])->setName('order.show');
$app->post('/order', ['shoppingCart\Controllers\OrderController', 'create'])->setName('order.create');

$app->get('/braintree/token', ['shoppingCart\Controllers\BraintreeController', 'token'])->setName('braintree.token');
