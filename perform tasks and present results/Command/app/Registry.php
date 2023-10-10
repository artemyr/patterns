<?php
namespace App;
class Registry {
    public static function getAccessManager(): AccessManager
    {
        return new AccessManager;
    }

}