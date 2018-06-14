<?php
namespace Ciebit\Places\Repositories\IBGE;

use Ciebit\Places\Repositories\Cities as ICities;
use Ciebit\Places\Collections\Cities as CitiesCollection;
use Ciebit\Places\City;
use Ciebit\Places\State;
use Ciebit\Places\Country;

class Cities implements ICities
{
    private $filterId;
    private $filterName;
    private $filterStateId;
    private $filterStateAbbreviation;
    private $filterStateName;
    private $total;

    private $Cities; #CitiesCollection

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
            if ($this->filterStateId) {
                $result = $this->applyFilterByStateId();
            } else {
                $result = $this->getAll();
            }
            if ($this->filterStateAbbreviation) {
                $result = $this->applyFilterByStateAbbreviation($result);
            }
            if ($this->filterStateName) {
                $result = $this->applyFilterByStateName($result);
            }
            if ($this->filterName) {
                $result = $this->applyFilterByName($result);
            }
            return $this->arrayToCollection($result);
        }
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

    private function applyFilterByStateAbbreviation($data): array
    {
        $data = array_filter($data, function($city) {
            return $city->microrregiao->mesorregiao->UF->sigla === $this->filterStateAbbreviation;
        });

        return $data;
    }

    private function applyFilterByStateName($data): array
    {
        $data = array_filter($data, function($city) {
            return $city->microrregiao->mesorregiao->UF->nome === $this->filterStateName;
        });

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
