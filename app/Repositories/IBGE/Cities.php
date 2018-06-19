<?php
namespace Ciebit\Places\Repositories\IBGE;

use Ciebit\Places\Repositories\Cities as ICities;
use Ciebit\Places\Collections\Cities as CitiesCollection;
use Ciebit\Places\City;
use Ciebit\Places\State;
use Ciebit\Places\Country;
use Ciebit\Places\Repositories\IBGE\States as StatesRepository;

class Cities implements ICities
{
    private $filterId;
    private $filterName;
    private $filterStateId;
    private $filterStateAbbreviation;
    private $filterStateName;
    private $total;
    private $flag_identical;

    private $Cities; #CitiesCollection
    private $States; #StatesRepository
    private $endpointPrefix; #string

    public function __construct(StatesRepository $states)
    {
        $this->States = $states;
        $this->endpointPrefix = "https://servicodados.ibge.gov.br/api/v1/localidades/";
    }

    public function get(): CitiesCollection
    {
        return $this->fetch();
    }

    public function setFilterId(int $id): ICities
    {
        $this->filterId = $id;
        return $this;
    }

    public function setFilterName(string $name, bool $identical = true): ICities
    {
        $this->filterName = $name;
        $this->flag_identical = $identical;
        return $this;
    }

    public function setFilterStateId(int $id): ICities
    {
        $this->filterStateId = $id;
        return $this;
    }

    public function setFilterStateName(string $name): ICities
    {
        $this->filterStateName = $name;
        return $this;
    }

    public function setFilterStateAbbreviation(string $abbreviation): ICities
    {
        $this->filterStateAbbreviation = $abbreviation;
        return $this;
    }

    public function setTotal(int $total): ICities
    {
        $this->total = $total;
        return $this;
    }

    private function fetch(): CitiesCollection
    {
        if ($this->filterId) {
            $cities = $this->getById($this->filterId);
        } elseif ($this->filterStateId) {
            $cities = $this->getByStateId($this->filterStateId);
        } elseif ($this->filterStateAbbreviation) {
            $cities = $this->getByStateAbbreviation();
        } elseif ($this->filterStateName) {
            $cities = $this->getByStateName();
        } else {
            $cities = $this->getAll();
        }

        $cities = $this->filterById($cities);
        $cities = $this->filterByStateId($cities);
        $cities = $this->filterByStateAbbreviation($cities);
        $cities = $this->filterByStateName($cities);
        $cities = $this->filterByName($cities);

        return $this->arrayToCollection($cities);
    }

    private function getAll(): array
    {
        $data = json_decode(file_get_contents("{$this->endpointPrefix}municipios"));
        return $data;
    }

    private function getById(int $id): ?array
    {
        $data = json_decode(file_get_contents("{$this->endpointPrefix}municipios/{$id}"));
        if ($data) {
            $array[] = $data;
            return $array;
        }
        return null;
    }

    private function getByStateId(int $id): ?array
    {
        $data = json_decode(file_get_contents("{$this->endpointPrefix}estados/{$id}/municipios"));
        if ($data) {
            return $data;
        }
        return null;
    }

    private function getByStateAbbreviation(): ?array
    {
        $states = $this->States->getAll();
        $state = array_filter($states, function($state) {
            return $state->sigla === $this->filterStateAbbreviation;
        });
        sort($state);

        if ($state[0]) {
            $data = json_decode(file_get_contents("{$this->endpointPrefix}estados/{$state[0]->id}/municipios"));
            return $data;
        }
        return null;
    }

    private function getByStateName(): ?array
    {
        $states = $this->States->getAll();
        $state = array_filter($states, function($state) {
            return $state->nome === $this->filterStateName;
        });
        sort($state);

        if ($state[0]) {
            $data = json_decode(file_get_contents("{$this->endpointPrefix}estados/{$state[0]->id}/municipios"));
            return $data;
        }
        return null;
    }

    public function filterById(array $cities): ?array
    {
        if (!$this->filterId) {
            return $cities;
        }
        $citiesFiltered = array_filter($cities, function($citie) {
            return $citie->id === $this->filterId;
        });
        return $citiesFiltered;
    }

    public function filterByName(array $cities): ?array
    {
        if (!$this->filterName) {
            return $cities;
        }
        $citiesFiltered = array_filter($cities, function($citie) {
            return $citie->nome === $this->filterName;
        });
        return $citiesFiltered;
    }

    public function filterByStateId(array $cities): ?array
    {
        if (!$this->filterStateId) {
            return $cities;
        }
        $citiesFiltered = array_filter($cities, function($citie) {
            return $citie->microrregiao->mesorregiao->UF->id === $this->filterStateId;
        });
        return $citiesFiltered;
    }

    public function filterByStateAbbreviation(array $cities): ?array
    {
        if (!$this->filterStateAbbreviation) {
            return $cities;
        }
        $citiesFiltered = array_filter($cities, function($citie) {
            return $citie->microrregiao->mesorregiao->UF->sigla === $this->filterStateAbbreviation;
        });
        return $citiesFiltered;
    }

    public function filterByStateName(array $cities): ?array
    {
        if (!$this->filterStateName) {
            return $cities;
        }
        $citiesFiltered = array_filter($cities, function($citie) {
            return $citie->microrregiao->mesorregiao->UF->nome === $this->filterStateName;
        });
        return $citiesFiltered;
    }

    private function arrayToCollection(array $data): CitiesCollection
    {
        $this->Cities = new CitiesCollection;
        foreach ($data as $item) {
            $State = new State(
                $item->microrregiao->mesorregiao->UF->nome,
                $item->microrregiao->mesorregiao->UF->id,
                $item->microrregiao->mesorregiao->UF->sigla,
                new Country("Brasil", 0, "BR")
            );
            $City = new City(
                $item->nome,
                $item->id,
                $State
            );
            $this->Cities->add($City);
        }
        return $this->Cities;
    }
}
