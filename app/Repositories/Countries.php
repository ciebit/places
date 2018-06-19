<?php
namespace Ciebit\Places\Repositories;

use Ciebit\Places\Collections\Countries as CountriesCollection;

interface Countries
{
    public function get(): CountriesCollection;
    public function setFilterId(int $id): self;
    public function setFilterAbbreviation(string $abbreviation): self;
    public function setFilterName(string $name, bool $identical = true): self;
    public function setTotal(int $total): self;
}
