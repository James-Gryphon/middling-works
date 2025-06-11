<?php
$Pacific = new Continent(
name: "Pacific Region",
territories: ["Vancouver", "Seattle", "Portland", "Sacramento", "San Francisco", "Los Angeles", "San Diego"]
);

var_dump($Pacific->territories);

class Continent {
public string $name;
public array $territories;
public string $agent_spot;

public function __construct(string $name = "Continent", array $territories = ["Albert"]){
    $this->name = $name;
    $this->territories = $territories;
    } // end Construct function
} // end class Continent

?>