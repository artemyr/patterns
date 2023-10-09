<?php
namespace App\Car\Ferrari;

use App\Car\CarInterface;
use App\Car\CarFactory;

class FerrariFactory implements CarFactory {
    public function createCar(): CarInterface {
        return new Ferrari();
    }
}