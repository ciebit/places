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

    private $Cities; #CitiesCollection
    private $States; #StatesRepository

    public function __construct(StatesRepository $states)
    {
        $this->States = $states;
    }

    public function get(): CitiesCollection
    {
        $this->Cities = new CitiesCollection;

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
            return $this->applyFilterById();
        } else {
            $result = $this->getState();
            if ($this->filterName) {
                $result = $this->applyFilterByName($result ?? $this->getAll());
            }
            return $this->arrayToCollection($result);
        }
    }

    private function getState(): ?array
    {
        $result = null;
        if ($this->filterStateId) {
            $result = $this->applyFilterByStateId();
        } else if ($this->filterStateAbbreviation) {
            $result = $this->States->getAll();
            $result = $this->applyFilterByStateAbbreviation($result);
        } else if ($this->filterStateName) {
            $result = $this->States->getAll();
            $result = $this->applyFilterByStateName($result);
        }
        return $result;
    }

    private function getAll(): array
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));
        return $data;
    }

    private function applyFilterById(): CitiesCollection
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/{$this->filterId}"));

        $State = new State(
            $data->microrregiao->mesorregiao->UF->nome,
            $data->microrregiao->mesorregiao->UF->id,
            $data->microrregiao->mesorregiao->UF->sigla,
            new Country("Brasil", 0, "BR")
        );
        $City = new City(
            $data->nome,
            $data->id,
            $State
        );
        return $this->Cities->add($City);
    }

    private function applyFilterByName($data): array
    {
        $data = array_filter($data, function($city) {
            return $city->nome === $this->filterName;
        });

        return $data;
    }

    private function applyFilterByStateId(): array
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$this->filterStateId}/municipios"));

        return $data;
    }

    private function applyFilterByStateAbbreviation($states): array
    {
        $state = array_filter($states, function($state) {
            return $state->sigla === $this->filterStateAbbreviation;
        });
        $state = array_merge(array(), $state);
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$state[0]->id}/municipios"));

        return $data;
    }

    private function applyFilterByStateName($states): array
    {
        $state = array_filter($states, function($state) {
            return $state->nome === $this->filterStateName;
        });
        $state = array_merge(array(), $state);
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$state[0]->id}/municipios"));

        return $data;
    }

    private function arrayToCollection($data): CitiesCollection
    {
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
