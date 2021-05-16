<?php


namespace App\Service\LoadData;


use App\Repository\UserRepository;

class UsersLoadData implements LoadDataInterface
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