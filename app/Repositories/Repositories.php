<?php
namespace Ciebit\Places\Repositories;

interface Repositories
{
    public function getCities(): CitiesRepository;
    public function getStates(): StatesRepository;
    public function getCountries(): CountriesRepository;
}
