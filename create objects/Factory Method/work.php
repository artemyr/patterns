<?php

use App\Ferrari\FerrariFactory;
use App\Lambo\LamboFactory;

$factory = new FerrariFactory();
$car = $factory->createCar();
$car->start();

echo "<br>";

$factory = new LamboFactory();
$car = $factory->createCar();
$car->start();