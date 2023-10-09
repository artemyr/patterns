<?php
namespace App\Car;
interface CarFactory {
    public function createCar(): Car;
}