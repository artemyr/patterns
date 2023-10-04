<?php

namespace App\AbstractFactory;

use App\Product\A\AbstractProductA;
use App\Product\B\AbstractProductB;
use App\Product\A\ConcreteProductA2;
use App\Product\B\ConcreteProductB2;

/**
 * Каждая Конкретная Фабрика имеет соответствующую вариацию продукта.
 */
class ConcreteFactory2 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA2();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB2();
    }
}
