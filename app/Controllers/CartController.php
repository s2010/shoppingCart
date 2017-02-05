<?php

namespace shoppingCart\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use shoppingCart\Basket\Basket;
use shoppingCart\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use shoppingCart\Basket\Exceptions\QuantityExceededException;

class CartController
{
    protected $basket;

    protected $product;

    public function __construct(Basket $basket, Product $product)
    {
        $this->basket = $basket;
        $this->product = $product;
    }

    public function index(Request $request, Response $response, Twig $view)
    {
        $this->basket->refresh();

        return $view->render($response, 'cart/index.twig');
    }

    public function add($slug, $quantity, Request $request, Response $response, Router $router)
    {
        $product = $this->product->where('slug', $slug)->first();

        if (!$product) {
            return $response->withRedirect($router->pathFor('home'));
        }

        try {
            $this->basket->add($product, $quantity);
        } catch (QuantityExceededException $e) {
            //
        }

        return $response->withRedirect($router->pathFor('cart.index'));
    }

    
    public function update($slug, Request $request, Response $response, Router $router)
    {
        $product = $this->product->where('slug', $slug)->first();

        if (!$product) {
            return $response->withRedirect($router->pathFor('home'));
        }

        try {
            $this->basket->update($product, $request->getParam('quantity'));
        } catch (QuantityExceededException $e) {
            //flash massage but users should not reach this point we can prevent within the form
        }

        return $response->withRedirect($router->pathFor('cart.index'));
    }
}
