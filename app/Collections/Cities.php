<?php
namespace Ciebit\Places\Collections;

use ArrayObject;
use ArrayIterator;
use Ciebit\Places\City;

class Cities
{
    private $Cities; #ArrayObject

    public function __construct()
    {
        $this->Cities = new ArrayObject;
    }

    public function add(City $city): self
    {
        $this->Cities->append($city);
        return $this;
    }

    public function getArrayIterator(): ArrayIterator
    {
        return $this->Cities->getIterator();
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->Cities;
    }

    public function total(): int
    {
        return $this->Cities->count();
    }
}
