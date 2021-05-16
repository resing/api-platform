<?php


namespace App\Service\ExportData;


use App\Repository\ProductRepository;
use App\Service\LoadData\LoadDataInterface;
use Psr\Log\LoggerInterface;

class ProductsExport implements DataExportInterface, LogFileInterface
{

    const PRODUCT = 'product';
    private $logger;
    private $productTransformer;

    public function __construct(LoadDataInterface $productTransformer, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->productTransformer = $productTransformer;
    }

    public function export(): string
    {
        $rows = [];
        foreach ($this->productTransformer->findAll() as $orderProduct) {
            $rows[] = implode(',',
                [
                    $orderProduct->getId(),
                    $orderProduct->getOwner()->getEmail(),
                    $orderProduct->getName(),
                    $orderProduct->getQuantity()
                ]);
        }

        return implode("\n", $rows);
    }

    public function nameFile(): string
    {
        return self::PRODUCT;
    }


    public function logDateGeneration(): void
    {
        $date = new \DateTime();
        $context = [
            'object' => self::PRODUCT,
            'dateCreation' => $date->format('Y-m-d'),
        ];
        $this->logger->info('The {object} file is created at {dateCreation}', $context);
    }
}
