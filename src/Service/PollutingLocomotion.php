<?php


namespace App\Service;


class PollutingLocomotion implements LocomotionInterface
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
        return '15';
    }

    public function resume()
    {
        return "You are energy {$this->energy()},
        and it costs ".$this->costByKm() * $this->getDistance()." euro";
    }

    public function getDistance()
    {
        return $this->distance;
    }
}
