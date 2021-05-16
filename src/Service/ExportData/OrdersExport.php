<?php


namespace App\Service\ExportData;

use App\Entity\OrderProduct;
use App\Repository\OrderProductRepository;
use App\Service\LoadData\LoadDataInterface;

class OrdersExport implements DataExportInterface
{

    private $loadData;

    public function __construct(LoadDataInterface $loadData)
    {
        $this->loadData = $loadData;
    }

    public function export(): string
    {
        $rows = [];
        /** @var  $orderProduct OrderProduct*/
        foreach ($this->loadData->findAll() as $orderProduct) {
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