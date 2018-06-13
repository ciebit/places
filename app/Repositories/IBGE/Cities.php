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

    public function get(): CitiesCollection
    {
        if ($this->filterId) {return $this->getById();}
        else if ($this->filterName) {return $this->getByName();}
        else if ($this->filterStateId) {return $this->getByStateId();}
        else if ($this->filterStateAbbreviation) {return $this->getByStateAbbreviation();}
        else if ($this->filterStateName) {return $this->getByStateName();}

        return $this->getAll();
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

    private function getAll(): CitiesCollection
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        return $this->arrayToCollection($data);
    }

    private function getById(): CitiesCollection
    {
        $Cities = new CitiesCollection;
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/{$this->filterId}"));

        $City = new City(
            $data->nome,
            $data->id,
            $data->microrregiao->mesorregiao->UF->nome
        );
        return $Cities->add($City);
    }

    private function getByName(): CitiesCollection
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        $data = array_filter($data, function($city) {
            return $city->nome === $this->filterName;
        });

        return $this->arrayToCollection($data);
    }

    private function getByStateId(): CitiesCollection
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$this->filterStateId}/municipios"));

        return $this->arrayToCollection($data);
    }

    private function getByStateAbbreviation(): CitiesCollection
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        $data = array_filter($data, function($city) {
            return $city->microrregiao->mesorregiao->UF->sigla === $this->filterStateAbbreviation;
        });

        return $this->arrayToCollection($data);
    }

    private function getByStateName(): CitiesCollection
    {
        $data = json_decode(file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios"));

        $data = array_filter($data, function($city) {
            return $city->microrregiao->mesorregiao->UF->nome === $this->filterStateName;
        });

        return $this->arrayToCollection($data);
    }

    private function arrayToCollection($data)
    {
        $Cities = new CitiesCollection;

        foreach ($data as $item) {
            $City = new City(
                $item->nome,
                $item->id,
                $item->microrregiao->mesorregiao->UF->nome
            );
            $Cities->add($City);
        }
        return $Cities;
    }
}
