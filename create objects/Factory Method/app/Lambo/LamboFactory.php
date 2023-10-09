<?php
namespace App\Lambo;

use App\Car\CarFactory;
use App\Car\Car;

class LamboFactory implements CarFactory {
    public function createCar(): Car {
        return new Lambo();
    }
}