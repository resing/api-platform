<?php


namespace App\Service\FindData;


use App\Repository\OrderRepository;

class LoadOrderData implements LoadDataInterface
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function findAll(): array
    {
        return $this->orderRepository->findAll();
    }
}
