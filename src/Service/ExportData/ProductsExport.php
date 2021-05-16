<?php


namespace App\Service\ExportData;


use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;

class ProductsExport implements DataExportInterface, LogFileInterface
{

    const PRODUCT = 'product';
    private $productRepository;

    private $logger;

    public function __construct(ProductRepository $productRepository, LoggerInterface $logger)
    {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    public function export(): string
    {
        $rows = [];
        foreach ($this->productRepository->findAll() as $orderProduct) {
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
