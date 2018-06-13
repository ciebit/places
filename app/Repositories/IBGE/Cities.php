<?php
namespace Ciebit\Places\Repositories\IBGE;

use Ciebit\Places\Repositories\Cities as ICities;
use Ciebit\Places\Collections\Cities as CitiesCollection;
use Ciebit\Places\City;

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

        if (! $this->applyFilters()) {
            $this->getAll();
        }

        return $this->Cities;
    }

    public function setFilterId(int $id): self
    {
        $this->filterId = $id;
        return $this;
    }

    public function setFilterName(string $name, bool $identical = true): self
    {
        $this->filterName = $name;
        return $this;
    }

    public function setFilterStateId(int $id): self
    {
        $this->$filterStateId = $id;
        return $this;
    }

    public function setFilterStateName(string $name): self
    {
        $this->filterStateName = $name;
        return $this;
    }

    public function setFilterStateAbbreviation(string $abbreviation): self
    {
        $this->filterStateAbbreviation = $abbreviation;
        return $this;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    private function applyFilters(): bool
    {
        if ($this->filterId) {return $this->applyFilterById();}
        if ($this->filterName) {return $this->applyFilterByName();}
        if ($this->filterStateId) {return $this->applyFilterByStateId();}
        if ($this->filterStateAbbreviation) {return $this->applyFilterByStateAbbreviation();}
        if ($this->filterStateName) {return $this->applyFilterByStateName();}
        return false;
    }

    private function getAll(): bool
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        $this->arrayToCollection($data);
        return true;
    }

    private function applyFilterById(): bool
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/{$this->filterId}"));

        $City = new City(
            $data->nome,
            $data->id,
            $data->microrregiao->mesorregiao->UF->nome
        );
        $this->Cities->add($City);
        return true;
    }

    private function applyFilterByName(): bool
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        $data = array_filter($data, function($city) {
            return $city->nome === $this->filterName;
        });

        $this->arrayToCollection($data);
        return true;
    }

    private function applyFilterByStateId(): bool
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$this->filterStateId}/municipios"));

        $this->arrayToCollection($data);
        return true;
    }

    private function applyFilterByStateAbbreviation(): bool
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        $data = array_filter($data, function($city) {
            return $city->microrregiao->mesorregiao->UF->sigla === $this->filterStateAbbreviation;
        });

        $this->arrayToCollection($data);
        return true;
    }

    private function applyFilterByStateName(): bool
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        $data = array_filter($data, function($city) {
            return $city->microrregiao->mesorregiao->UF->nome === $this->filterStateName;
        });

        $this->arrayToCollection($data);
        return true;
    }

    private function arrayToCollection($data): void
    {
        foreach ($data as $item) {
            $City = new City(
                $item->nome,
                $item->id,
                $item->microrregiao->mesorregiao->UF->nome
            );
            $this->Cities->add($City);
        }
        $this->Cities;
    }
}
