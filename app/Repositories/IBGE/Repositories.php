<?php
namespace Ciebit\Places\Repositories\IBGE;

use Ciebit\Places\Repositories\Repositories as IRepositories;
use Ciebit\Places\Repositories\Cities as ICitiesRepository;
use Ciebit\Places\Repositories\States as IStatesRepository;
use Ciebit\Places\Repositories\Countries as ICountriesRepository;
use Ciebit\Places\Repositories\IBGE\States as StatesRepository;
use Ciebit\Places\Repositories\IBGE\Countries as CountriesRepository;

class Repositories implements IRepositories
{
    public function getCities(): ICitiesRepository
    {
        return new Cities(new StatesRepository(new CountriesRepository));
    }

    public function getStates(): IStatesRepository
    {
        return new States(new CountriesRepository);
    }

    public function getCountries(): ICountriesRepository
    {
        return new Countries;
    }
}
