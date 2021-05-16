<?php


namespace App\Service\ExportData;

use App\Repository\UserRepository;

class UsersExport implements DataExportInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function export(): string
    {
        $rows = [];
        foreach ($this->userRepository->findAll() as $user) {
            $rows[] = implode(',', [$user->getId(), $user->getUsername(), $user->getEmail(), $user->getPhoneNumber()]);
        }

        return implode("\n", $rows);
    }


    public function nameFile(): string
    {
        return 'user';
    }
}
