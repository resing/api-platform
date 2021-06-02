<?php

declare(strict_types=1);

namespace App\Service;


class TravelCost
{
    public function resume(Locomotion $locomotion): string
    {
        return "You are energy {$locomotion->energy()},
        and it costs ".$locomotion->costByKm() * $locomotion->getDistance()." euro";
    }
}
