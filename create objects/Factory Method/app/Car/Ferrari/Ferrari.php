<?php
namespace App\Car\Ferrari;

use App\Car\CarInterface;

class Ferrari implements CarInterface {
    public function start(): void {
        echo "Starting Ferrari...";
    }
}