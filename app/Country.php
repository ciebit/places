<?php
namespace Ciebit\Places;

class Country
{
    private $name; #string
    private $abbreviation; #string
    private $id; #int

    public function __construct(string $name, int $id, string $abbreviation)
    {
        $this->name = $name;
        $this->id = $id;
        $this->abbreviation = $abbreviation;
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
}
