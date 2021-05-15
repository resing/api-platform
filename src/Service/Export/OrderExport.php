<?php


namespace App\Service\Export;

use App\Repository\OrderRepository;
use App\Service\FindData\LoadDataInterface;

class OrderExport implements ExportDataInterface
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function export(): string
    {
        $rows = [];
        foreach ($this->orderRepository->findAll() as $user) {
            $rows[] = implode(',',
                [
                    $user->getId(),
                    $user->getOwner()->getEmail(),
                    $user->getProduct()->getName(),
                    $user->getQuantity()
                ]);
        }

        return implode("\n", $rows);
    }

    public function nameFile(): string
    {
        return 'order';
    }
}
