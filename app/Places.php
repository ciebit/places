<?php
namespace Ciebit\Places;

use Ciebit\Places\Repositories\IBGE\Repository as IBGERepository;

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
    public const TOTAL = null; #string

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

    private function applyCitiesFilters(Repository $Repository)
    {
        if ($this->filters[CITY_ID])
            {$Repository->setFilterId($this->filters[CITY_ID]);}

        if ($this->filters[CITY_NAME])
            {$Repository->setFilterName($this->filters[CITY_NAME]);}

        if ($this->filters[STATE_ID])
            {$Repository->setFilterStateId($this->filters[STATE_ID]);}

        if ($this->filters[STATE_NAME])
            {$Repository->setFilterStateName($this->filters[STATE_NAME]);}

        if ($this->filters[STATE_ABBREVIATION])
            {$Repository->setFilterStateAbbreviation($this->filters[STATE_ABBREVIATION]);}
    }

    private function applyStatesFilters(Repository $Repository)
    {
        if ($this->filters[STATE_ID])
            {$Repository->setFilterId($this->filters[STATE_ID]);}

        if ($this->filters[STATE_NAME])
            {$Repository->setFilterName($this->filters[STATE_NAME]);}

        if ($this->filters[STATE_ABBREVIATION])
            {$Repository->setFilterAbbreviation($this->filters[STATE_ABBREVIATION]);}
    }

    private function applyCountriesFilters(Repository $Repository)
    {
        if ($this->filters[COUNTRY_ID])
            {$Repository->setFilterId($this->filters[COUNTRY_ID]);}

        if ($this->filters[COUNTRY_NAME])
            {$Repository->setFilterName($this->filters[COUNTRY_NAME]);}

        if ($this->filters[COUNTRY_ABBREVIATION])
            {$Repository->setFilterAbbreviation($this->filters[COUNTRY_ABBREVIATION]);}
    }
}
