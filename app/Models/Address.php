<?php

namespace shoppingCart\Models;

use shoppingCart\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'address1',
        'address2',
        'city',
        'postal_code',
    ];
}
