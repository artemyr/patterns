<?php
class UnitException extends \Exception {

}
abstract class Unit {
    public function getComposite() {
        return null;
    }
    abstract public function bombardStrength(): int;
}
abstract class CompositeUnit extends Unit {
    private $units = [];
    public function getComposite() : CompositeUnit
    {
        return $this;
    }
    public function addUnit(Unit $unit) {
        if (in_array($unit, $this->units)) return;
        $this->units[] = $unit;
    }
    public function removeUnit(Unit $unit)
    {
        $idx = array_search($unit, $this->units, true);
        if (is_int($idx)) {
            array_splice($this->units, $idx, 1, []);
        }
    }
    public function getUnits(): array {
        return $this->units;
    }
}
class Archer extends Unit {
    public function bombardStrength(): int
    {
        return 4;
    }
}
class LaserCannonUnit extends Unit {
    public function bombardStrength(): int
    {
        return 44;
    }
}
class Army extends CompositeUnit {
    private $units = [];
    public function addUnit(Unit $unit) {
        if (in_array($unit, $this->units)) return;
        $this->units[] = $unit;
    }
    public function bombardStrength(): int {
        $ret = 0;
        foreach ($this->units as $unit) {
            $ret += $unit->bombardStrength();
        }
        return $ret;
    }

    public function removeUnit(Unit $unit)
    {
        $idx = array_search($unit, $this->units, true);
        if (is_int($idx)) {
            array_splice($this->units, $idx, 1, []);
        }
    }
}

class Cavalery extends CompositeUnit {

    public function bombardStrength(): int
    {
        return 10;
    }
}
// лошадь
class TroopCarrier extends CompositeUnit {
    public function addUnit(Unit $unit)
    {
        // бронетранспортер
        if ($unit instanceof Cavalery) {
            throw new UnitException("Нельзя перемещать лошадь на бронетранспортере.");
        }
        parent::addUnit($unit);
    }
    public function bombardStrength(): int
    {
        return 0;
    }
}

class UnitScript {
    public static function joinExisting(Unit $newUnit, Unit $occupyingUnit) : CompositeUnit {
        $comp = $occupyingUnit->getComposite();
        if (! is_null($comp)) {
            $comp->addUnit($newUnit);
        } else {
            $comp = new Army();
            $comp->addUnit($occupyingUnit);
            $comp->addUnit($newUnit);
        }
        return $comp;
    }
}

$unit1 = new Archer();
$unit2 = new LaserCannonUnit();
$unit3 = UnitScript::joinExisting($unit1, $unit2);
echo "<pre>";
print_r($unit3);

echo "Начинаются зависимости";

$unit1 = new Cavalery();
$unit2 = new TroopCarrier();
$unit3 = UnitScript::joinExisting($unit1, $unit2);
echo "<pre>";
print_r($unit3);
