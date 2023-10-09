<?php

use App\Car\Ferrari\FerrariFactory;
use App\Car\Lambo\LamboFactory;

$factory = new FerrariFactory();
$car = $factory->createCar();
$car->start();

echo "<br>";

$factory = new LamboFactory();
$car = $factory->createCar();
$car->start();