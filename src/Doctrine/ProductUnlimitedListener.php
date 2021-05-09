<?php

namespace App\Doctrine;

use App\Entity\Product;

class ProductUnlimitedListener
{
    public function postLoad(Product $product)
    {
        $product->setUnlimited(100 < $product->getQuantity());
    }
}
