<?php

namespace shoppingCart\Handlers;

use shoppingCart\Handlers\Contracts\HandlerInterface;

class EmptyBasket implements HandlerInterface
{
    public function handle($event)
    {
        $event->basket->clear();
    }
}
