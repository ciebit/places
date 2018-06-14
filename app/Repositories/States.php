<?php
namespace Ciebit\Places\Repositories;

use Ciebit\Places\Collections\States as StatesCollection;

interface States
{
    public function get(): StatesCollection;
    public function setFilterId(int $id): self;
    public function setFilterAbbreviation(string $abbreviation): self;
    public function setFilterName(string $name, bool $identical = true): self;
    public function setFilterCountryId(int $id): self;
    public function setTotal(int $total): self;
}
