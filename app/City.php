<?php
namespace Ciebit\Places;

class City
{
    private $name; #string
    private $id; #int
    private $State; #State

    public function __construct(string $name, int $id, State $State)
    {
        $this->name = $name;
        $this->id = $id;
        $this->State = $State;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getState(): State
    {
        return $this->State;
    }
}
