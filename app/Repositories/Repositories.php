<?php
namespace Ciebit\Places\Repositories;

interface Repositories
{
    public function getCities(): Cities;
    public function getStates(): States;
    public function getCountries(): Countries;
}
