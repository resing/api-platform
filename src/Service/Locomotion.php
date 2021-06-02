<?php


namespace App\Service;


class Locomotion
{
    private $distance;

    public function __construct(int $distance)
    {
        $this->distance = $distance;
    }

    public function energy()
    {
        return 'gasoline';
    }

    public function costByKm()
    {
        return 15;
    }

    public function getDistance()
    {
        return $this->distance;
    }
}
