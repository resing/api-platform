<?php


namespace App\Service;


class GreenLocomotion implements LocomotionInterface
{

    private $distance;

    public function __construct(int $distance)
    {
        $this->distance = $distance;
    }
    public function resume()
    {
        return "Green energy, it cost nothing for {$this->getDistance()}";
    }

    public function getDistance()
    {
       return $this->distance;
    }
}
