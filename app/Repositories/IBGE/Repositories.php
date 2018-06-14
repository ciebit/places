<?php
namespace Ciebit\Places\Repositories\IBGE;

use Ciebit\Places\Repositories\Repositories as IRepositories;
use Ciebit\Places\Repositories\Cities as CitiesRepository;
use Ciebit\Places\Repositories\States as StatesRepository;
use Ciebit\Places\Repositories\Countries as CountriesRepository;

class Repositories implements IRepositories
{
    public function getCities(): CitiesRepository
    {
        return new Cities;
    }

    public function getStates(): StatesRepository
    {
        return new States;
    }

    public function getCountries(): CountriesRepository
    {
        return new Countries;
    }
}
