<?php

namespace App;

use App\AbstractFactory\AbstractFactory;
use App\AbstractFactory\ConcreteFactory1;
use App\AbstractFactory\ConcreteFactory2;

/**
 * Клиентский код работает с фабриками и продуктами только через абстрактные
 * типы: Абстрактная Фабрика и Абстрактный Продукт. Это позволяет передавать
 * любой подкласс фабрики или продукта клиентскому коду, не нарушая его.
 */
function clientCode(AbstractFactory $factory)
{
    $productA = $factory->createProductA();
    $productB = $factory->createProductB();

    echo $productB->usefulFunctionB() . "<br>";
    echo $productB->anotherUsefulFunctionB($productA) . "<br>";
}

/**
 * Клиентский код может работать с любым конкретным классом фабрики.
 */
echo "Client: Testing client code with the first factory type:<br>";
clientCode(new ConcreteFactory1());

echo "<br>";

echo "Client: Testing the same client code with the second factory type:<br>";
clientCode(new ConcreteFactory2());