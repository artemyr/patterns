<?php
namespace App;
class AccessManager {
    private $error;
    public function login($user, $pass) {
        if ($user != 'bob') {
            $this->error = "Неверный логин";
            return null;
        }

        if ($pass != 'pass') {
            $this->error = "Неверный пароль";
            return null;
        }

        if ($user == 'bob' && $pass == 'pass')
            return new User($user);
    }
    public function getError() {
        return $this->error;
    }
}