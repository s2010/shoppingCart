<?php

namespace shoppingCart\Handlers;

use shoppingCart\Handlers\Contracts\HandlerInterface;

class MarkOrderPaid implements HandlerInterface
{
    public function handle($event)
    {
        $event->order->update([
            'paid' => true,
        ]);
    }
}
