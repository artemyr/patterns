<?php
namespace App;
class User {
    private $name;
    public function __construct(string $user) {
        $this->name = $user;
    }
    public function getUserName() {
        return $this->name;
    }
}