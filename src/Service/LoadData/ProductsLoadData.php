<?php


namespace App\Service\LoadData;


use App\Repository\ProductRepository;

class ProductsLoadData implements LoadDataInterface
{

    private $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function findAll(): array
    {
        return $this->productRepository->findAll();
    }
}
