<?php
namespace Places\Models\Repositories\IBGE;

use Ciebit\Places\Repositories\Repositories as IRepositories;

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
