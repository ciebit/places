<?php
namespace Ciebit\Places\Collections;

use ArrayObject;
use ArrayIterator;

class Countries
{
    private $Countries; #ArrayObject

    public function __construct()
    {
        $this->Countries = new ArrayObject;
    }

    public function add(Country $country): self
    {
        $this->Countries->append($country);
    }

    public function getArrayIterator(): ArrayIterator
    {
        $this->Countries->getIterator();
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->Countries;
    }

    public function total(): int
    {
        return $this->Countries->count();
    }
}
