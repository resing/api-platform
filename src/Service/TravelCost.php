<?php


namespace App\Service;


class TravelCost
{
    public function resume(LocomotionInterface $locomotion): string
    {
        return $locomotion->resume();
    }
}
