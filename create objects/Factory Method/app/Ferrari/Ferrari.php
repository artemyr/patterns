<?php
namespace App\Ferrari;

use App\Car\Car;

class Ferrari implements Car {
    public function start(): void {
        echo "Starting Ferrari...";
    }
}