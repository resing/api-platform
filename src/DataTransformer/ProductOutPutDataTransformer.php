<?php


namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ProductOutPut;
use App\Entity\Product;

class ProductOutPutDataTransformer implements DataTransformerInterface
{
    public function transform($product, string $to, array $context = [])
    {
        return ProductOutPut::createFromEntity($product);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof Product && $to === ProductOutPut::class;
    }
}
