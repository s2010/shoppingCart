<?php
/**
 * Created by PhpStorm.
 * User: Bashayer
 * Date: 1/14/17
 * Time: 2:42 PM
 */

namespace shoppingCart\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class HomeController
{
    public function index(Request $request, Response $response, Twig $view, Product $product)
    {
        $products = $product->get();

        return $view->render($response, 'home.twig', [
            'products' => $products
        ]);
    }
}