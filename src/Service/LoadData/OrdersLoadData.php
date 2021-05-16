<?php


namespace App\Service\LoadData;


use App\Repository\OrderProductRepository;

class OrdersLoadData implements LoadDataInterface
{
    private $orderProductRepository;

    public function __construct(OrderProductRepository $orderProductRepository)
    {

        $this->orderProductRepository = $orderProductRepository;
    }

    public function findAll(): array
    {
        return $this->orderProductRepository->findAll();
    }
}
