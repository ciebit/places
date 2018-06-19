<?php
namespace Ciebit\Places\Repositories;

use Ciebit\Places\Collections\Cities as CitiesCollection;

interface Cities
{
    public function get(): CitiesCollection;
    public function setFilterId(int $id): self;
    public function setFilterName(string $name, bool $identical = true): self;
    public function setFilterStateId(int $id): self;
    public function setFilterStateAbbreviation(string $abbreviation): self;
    public function setFilterStateName(string $name): self;
    public function setTotal(int $total): self;
}
