<?php


namespace App\Service;


class Bike extends Locomotion
{
    public function __construct(int $distance)
    {
        parent::__construct($distance);
    }

    public function energy(): string
    {
        return '';
    }

    public function costByKm()
    {
        return '';
    }
}
