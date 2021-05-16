<?php


namespace App\Service\ExportData;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\LoadData\LoadDataInterface;

class UsersExport implements DataExportInterface
{
    private $loadData;

    public function __construct(LoadDataInterface $transformer)
    {
        $this->loadData = $transformer;
    }

    public function export(): string
    {
        $rows = [];
        /** @var  User $user */
        foreach ($this->loadData->findAll() as $user) {
            $rows[] = implode(',', [$user->getId(), $user->getUsername(), $user->getEmail(), $user->getPhoneNumber()]);
        }

        return implode("\n", $rows);
    }


    public function nameFile(): string
    {
        return 'user';
    }
}
