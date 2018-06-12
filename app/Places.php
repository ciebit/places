<?php
namespace Ciebit\Places;

use Ciebit\Places\Repositories\IBGE\Repository as IBGERepository;

class Places
{
    public const CITY_ID = null; #string
    public const CITY_INITIALS = null; #string
    public const CITY_NAME = null; #string
    public const COUNTRY_ID = null; #string
    public const COUNTRY_INITIALS = null; #string
    public const COUNTRY_NAME = null; #string
    public const STATE_ID = null; #string
    public const STATE_INITIALS = null; #string
    public const STATE_NAME = null; #string
    public const TOTAL = null; #string

    private $Repository; #Repositories

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
        $codeColumn = $value;
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
        $Repository->setFilterId(CITY_ID);
        $Repository->setFilterName(CITY_NAME);
        $Repository->setFilterInitial(CITY_INITIALS);
    }

    private function applyStatesFilters(Repository $Repository)
    {
        $Repository->setFilterId(STATE_ID);
        $Repository->setFilterName(STATE_NAME);
        $Repository->setFilterInitial(STATE_INITIALS);
    }

    private function applyCountriesFilters(Repository $Repository)
    {
        $Repository->setFilterId(COUNTRY_ID);
        $Repository->setFilterName(COUNTRY_NAME);
        $Repository->setFilterInitial(COUNTRY_INITIALS);
    }
}
