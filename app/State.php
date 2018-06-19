<?php
namespace Ciebit\Places;

class State
{
    private $name; #string
    private $abbreviation; #string
    private $id; #int
    private $State; #State

    public function __construct(string $name, int $id, string $abbreviation, Country $country)
    {
        $this->name = $name;
        $this->id = $id;
        $this->abbreviation = $abbreviation;
        $this->Country = $country;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAbbreviations(): string
    {
        return $this->abbreviation;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): Country
    {
        return $this->Country;
    }
}
