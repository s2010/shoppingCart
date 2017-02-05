<?php

namespace shoppingCart\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use shoppingCart\Models\Order;
use shoppingCart\Basket\Basket;
use shoppingCart\Models\Product;
use shoppingCart\Models\Address;
use shoppingCart\Models\Customer;
use shoppingCart\Validation\Contracts\ValidatorInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use shoppingCart\Validation\Forms\OrderForm;
use Braintree_Transaction;

class OrderController
{
    protected $basket;

    protected $router;

    protected $validator;

    public function __construct(Basket $basket, Router $router, ValidatorInterface $validator)
    {
        $this->basket = $basket;
        $this->router = $router;
        $this->validator = $validator;
    }

    public function index(Request $request, Response $response, Twig $view)
    {
        $this->basket->refresh();

        if (!$this->basket->subTotal()) {
            return $response->withRedirect($this->router->pathFor('cart.index'));
        }

        return $view->render($response, 'order/index.twig');
    }

    public function show($hash, Request $request, Response $response, Twig $view, Order $order)
    {
        $order = $order->with(['address', 'products'])->where('hash', $hash)->first();

        if (!$order) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        return $view->render($response, 'order/show.twig', [
            'order' => $order,
        ]);
    }

    public function create(Request $request, Response $response, Customer $customer, Address $address)
    {
        $this->basket->refresh();

        if (!$this->basket->subTotal()) {
            return $response->withRedirect($this->router->pathFor('cart.index'));
        }

        if (!$request->getParam('payment_method_nonce')) {
            return $response->withRedirect($this->router->pathFor('order.index'));
        }

        $validation = $this->validator->validate($request, OrderForm::rules());

        if ($validation->fails()) {
            return $response->withRedirect($this->router->pathFor('order.index'));
        }

        $hash = bin2hex(random_bytes(32));

        $customer = $customer->firstOrCreate([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
        ]);

        $address = $address->firstOrCreate([
            'address1' => $request->getParam('address1'),
            'address2' => $request->getParam('address2'),
            'city' => $request->getParam('city'),
            'postal_code' => $request->getParam('postal_code'),
        ]);

        $order = $customer->orders()->create([
            'hash' => $hash,
            'paid' => false,
            'total' => $this->basket->subTotal() + 5,
            'address_id' => $address->id,
        ]);

        $order->products()->saveMany(
            $this->basket->all(),
            $this->getQuantities($this->basket->all())
        );

        //payment handling
        $result = Braintree_Transaction::sale([
            'amount' => $this->basket->subTotal() + 5,
            'paymentMethodNonce' => $request->getParam('payment_method_nonce'),
            'options' => [
                'submitForSettlement' => true,
            ]
        ]);

        //after payment handling events(update stock, mark order paid,empty basket)
        $event = new \shoppingCart\Events\OrderWasCreated($order, $this->basket);

        if (!$result->success) {
            $event->attach(new \shoppingCart\Handlers\RecordFailedPayment);
            $event->dispatch();

            return $response->withRedirect($this->router->pathFor('order.index'));
        }

        $event->attach([
            new \shoppingCart\Handlers\MarkOrderPaid,
            new \shoppingCart\Handlers\RecordSuccessfulPayment($result->transaction->id),
            new \shoppingCart\Handlers\UpdateStock,
            new \shoppingCart\Handlers\EmptyBasket,
        ]);

        $event->dispatch();

        return $response->withRedirect($this->router->pathFor('order.show', [
            'hash' => $hash,
        ]));
    }

    protected function getQuantities($items)
    {
        $quantities = [];

        foreach ($items as $item) {
            $quantities[] = ['quantity' => $item->quantity];
        }

        return $quantities;
    }
}
