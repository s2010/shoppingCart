<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:41 PM
 */

$app->get('/',['Cart\Controllers\HomeController', 'index'])->setName('home');