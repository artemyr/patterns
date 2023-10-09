<?php
namespace App\Lambo;

use App\Car\Car;

class Lambo implements Car {
    public function start(): void {
        echo "Starting Lambo...";
    }
}