<?php
namespace Ciebit\Places;

use Ciebit\Places\Repositories\Repositories;
use Ciebit\Places\Repositories\Cities as CitiesRepository;
use Ciebit\Places\Repositories\States as StatesRepository;
use Ciebit\Places\Repositories\Countries as CountriesRepository;
use Ciebit\Places\Repositories\IBGE\Repositories as IBGERepository;

class Places
{
    public const CITY_ID = 'city-id';
    public const CITY_NAME = 'city-name';
    public const COUNTRY_ID = 'country-id';
    public const COUNTRY_ABBREVIATION = 'country-abbreviation';
    public const COUNTRY_NAME = 'country-name';
    public const STATE_ID = 'state-id';
    public const STATE_ABBREVIATION = 'state-abbreviation';
    public const STATE_NAME = 'state-name';
    public const TOTAL = 'total';

    private $Repository; #Repositories
    private $filters; #array

    public function __construct(Repositories $Repository = null)
    {
        if (!$Repository) {
            $this->Repository = new IBGERepository;
        } else {
            $this->Repository = $Repository;
        }
    }

    public function addFilter(string $codeColumn, $value): self
    {
        $this->filters[$codeColumn] = $value;
        return $this;
    }

    public function getCities(): CitiesCollection
    {
        $Cities = $this->Repository->getCities();
        $this->applyCitiesFilters($Cities);
        return $Cities->get();
    }

    public function getStates(): StatesCollection
    {
        $States = $this->Repository->getStates();
        $this->applyStatesFilters($States);
        return $States->get();
    }

    public function getCountries(): CountriesCollection
    {
        $Countries = $this->Repository->getCountries();
        $this->applyCountriesFilters($Countries);
        return $Countries->get();
    }

    private function applyCitiesFilters(CitiesRepository $Repository)
    {
        $Repository
        ->setFilterId($this->filters[self::CITY_ID] ?? 0)
        ->setFilterName($this->filters[self::CITY_NAME] ?? '')
        ->setFilterStateId($this->filters[self::STATE_ID] ?? 0)
        ->setFilterStateName($this->filters[self::STATE_NAME] ?? '')
        ->setFilterStateAbbreviation($this->filters[self::STATE_ABBREVIATION] ?? '')
        ->setTotal($this->filters[self::TOTAL] ?? 0);
    }

    private function applyStatesFilters(StatesRepository $Repository)
    {
        $Repository
        ->setFilterId($this->filters[self::STATE_ID] ?? 0)
        ->setFilterName($this->filters[self::STATE_NAME] ?? '')
        ->setFilterAbbreviation($this->filters[self::STATE_ABBREVIATION] ?? '')
        ->setTotal($this->filters[self::TOTAL] ?? 0);
    }

    private function applyCountriesFilters(CountriesRepository $Repository)
    {
        $Repository
        ->setFilterId($this->filters[self::COUNTRY_ID] ?? 0)
        ->setFilterName($this->filters[self::COUNTRY_NAME] ?? '')
        ->setFilterAbbreviation($this->filters[self::COUNTRY_ABBREVIATION] ?? '')
        ->setTotal($this->filters[self::TOTAL] ?? 0);
    }
}
