<?php
namespace App\Car\Lambo;

use App\Car\CarInterface;

class Lambo implements CarInterface {
    public function start(): void {
        echo "Starting Lambo...";
    }
}