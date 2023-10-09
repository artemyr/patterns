<?php
namespace App\Car\Lambo;

use App\Car\CarInterface;
use App\Car\CarFactory;

class LamboFactory implements CarFactory {
    public function createCar(): CarInterface {
        return new Lambo();
    }
}