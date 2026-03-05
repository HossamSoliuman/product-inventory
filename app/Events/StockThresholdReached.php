<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;

class StockThresholdReached
{
    use Dispatchable;

    public function __construct(public readonly Product $product) {}
}
