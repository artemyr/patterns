<?php
class UnitException extends \Exception {

}
abstract class Unit {
    public function addUnit(Unit $unit) {
        throw new UnitException(get_class($this) . " относится к листьям");
    }
    public function removeUnit(Unit $unit) {
        throw new UnitException(get_class($this) . " относится к листьям");
    }
    abstract public function bombardStrength(): int;
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
class Army extends Unit {
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

$main_army = new Army();
$main_army->addUnit(new Archer());
$main_army->addUnit(new LaserCannonUnit());

$sub_army = new Army();
$sub_army->addUnit(new Archer());
$sub_army->addUnit(new Archer());
$sub_army->addUnit(new LaserCannonUnit());

$main_army->addUnit($sub_army);

print_r($main_army->bombardStrength());

echo "<br>пытаемся прибавить солдата к лазеру";

$unit1 = new Archer();
$unit2 = new LaserCannonUnit();
$unit1->addUnit($unit2);
