<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\WeatherUtil;
use App\Repository\WeatherRepository;
use App\Repository\CityRepository;

#[AsCommand(
    name: 'app:getByCountryAndCityName',
    description: 'Add a short description for your command',
)]
class GetByCountryAndCityNameCommand extends Command
{
    private WeatherUtil $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'country')
            ->addArgument('city', InputArgument::REQUIRED, 'city')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');

        if ($country && $city) {
            $result = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);
            $output->writeln(str_replace(' ', '', "City:{$result[0]->getCityname()}\n
                                Weather_type:{$result[0]->getType()}\n
                                Temperature:{$result[0]->getTemperature()} C\n
                                Wind:{$result[0]->getWind()} km/h\n
                                Humidity:{$result[0]->getHumidity()} %\n
                                Precipitation_chance:{$result[0]->getPrecipitation()} %\n"));
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}