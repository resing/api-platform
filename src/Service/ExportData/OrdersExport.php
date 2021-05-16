<?php


namespace App\Service\ExportData;

use App\Repository\OrderProductRepository;

class OrdersExport implements DataExportInterface
{
    private $orderProductRepository;

    public function __construct(OrderProductRepository $orderProductRepository)
    {

        $this->orderProductRepository = $orderProductRepository;
    }

    public function export(): string
    {
        $rows = [];
        foreach ($this->orderProductRepository->findAll() as $orderProduct) {
            $rows[] = implode(',',
                [
                    $orderProduct->getId(),
                    $orderProduct->getCommand()->getOwner()->getEmail(),
                    $orderProduct->getProduct()->getName(),
                    $orderProduct->getQuantity()
                ]);
        }

        return implode("\n", $rows);
    }

    public function nameFile(): string
    {
        return 'order';
    }
}