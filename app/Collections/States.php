<?php
namespace Ciebit\Places\Collections;

use ArrayObject;
use ArrayIterator;

class States
{
    private $States; #ArrayObject

    public function __construct()
    {
        $this->States = new ArrayObject;
    }

    public function add(State $state): self
    {
        $this->States->append($state);
    }

    public function getArrayIterator(): ArrayIterator
    {
        $this->States->getIterator();
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->States;
    }

    public function total(): int
    {
        return $this->States->count();
    }
}
