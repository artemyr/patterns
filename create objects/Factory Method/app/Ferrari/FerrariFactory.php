<?php
namespace App\Ferrari;

use App\Car\CarFactory;
use App\Car\Car;

class FerrariFactory implements CarFactory {
    public function createCar(): Car {
        return new Ferrari();
    }
}