<?php

namespace App\Command;

use App\Service\Bike;
use App\Service\Locomotion;
use App\Service\TravelCost;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LocomotionCommand extends Command
{
    protected static $defaultName = 'app:locomotion';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
         // use case One
//        $locomotion = new Locomotion(10);
//        $travelCost= new TravelCost();
//        $result = $travelCost->resume($locomotion);
//        $output->writeln($result);
        $bike = new Bike(4);
        $travelCost = new TravelCost();
        $result = $travelCost->resume($bike);
        $output->writeln($result);
        return Command::SUCCESS;
    }
}
