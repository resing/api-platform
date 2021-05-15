<?php


namespace App\Service\FindData;


use App\Repository\UserRepository;

class LoadDataUser implements LoadDataInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }
}
