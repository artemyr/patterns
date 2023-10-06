<?php
class UnitException extends \Exception {

}
abstract class Unit {
    protected int $depth = 0;
    public function accept(ArmyVisitor $visitor) {
        $refthis = new \ReflectionClass(get_class($this));
        $method = "visit" . $refthis->getShortName();
        $visitor->$method($this);
    }
    protected function setDepth($depth) {
        $this->depth = $depth;
    }
    public function getDepth() {
        return $this->depth;
    }
    public function getComposite() {
        return null;
    }
    abstract public function bombardStrength(): int;
    public function textDump($num = 0): string
    {
        $txtout = "";
        $pad = 4 * $num;
        $txtout .= sprintf("%{$pad}s", "");
        $txtout .= get_class($this).": ";
        $txtout .= "Огневая мощь: " . $this->bombardStrength()."<br>";
        return $txtout;
    }
}
abstract class CompositeUnit extends Unit {
    protected $units = [];
    public function getComposite() : CompositeUnit
    {
        return $this;
    }
    public function addUnit(Unit $unit) {
        if (in_array($unit, $this->units)) return;
        $unit->setDepth($this->depth + 1);
        $this->units[] = $unit;
    }
    public function accept(ArmyVisitor $visitor) {
        parent::accept($visitor);
        foreach ($this->units as $unit) {
            $unit->accept($visitor);
        }
    }
    public function removeUnit(Unit $unit)
    {
        $idx = array_search($unit, $this->units, true);
        if (is_int($idx)) {
            array_splice($this->units, $idx, 1, []);
            $this->setDepth($this->depth - 1);
        }
    }
    public function getUnits(): array {
        return $this->units;
    }
    public function textDump($num = 0): string
    {
        $txtout = parent::textDump($num);
        foreach ($this->units as $unit) {
            $txtout .= $unit->textDump($num + 1);
        }
        return $txtout;
    }
}
abstract class ArmyVisitor {
    abstract public function visit(Unit $node);
    public function visitArcher(Archer $node) {
        $this->visit($node);
    }
    public function visitCavalry(Cavalry $node) {
        $this->visit($node);
    }
    public function visitLaserCannonUnit(LaserCannonUnit $node) {
        $this->visit($node);
    }
    public function visitTroopCarrierUnit(Archer $node) {
        $this->visit($node);
    }
    public function visitArmy(Army $node) {
        $this->visit($node);
    }
}
class TextDumpArmyVisitor extends ArmyVisitor {
    private $text = "";
    public function visit(Unit $node)
    {
        $txt = "";
        $pad = 4 * $node->getDepth();
        $txt .= sprintf("%{$pad}s", "");
        $txt .= "({$node->getDepth()}) " . get_class($node) . ": ";
        $txt .= " Огневая мощь: ".$node->bombardStrength() . "<br>";
        $this->text .= $txt;
    }
    public function getText() {
        return $this->text;
    }
}
class TaxCollectionVisitor extends ArmyVisitor {
    private $due = 0;
    private $report = "";
    public function visit(Unit $node)
    {
        $this->levy($node, 1);
    }
    public function visitArcher(Archer $node) {
        $this->levy($node, 2);
    }
    public function visitCavalry(Cavalry $node) {
        $this->levy($node, 3);
    }
    public function visitLaserCannonUnit(LaserCannonUnit $node) {
        $this->levy($node, 4);
    }
    public function visitTroopCarrierUnit(Archer $node) {
        $this->levy($node, 5);
    }
    public function visitArmy(Army $node) {
        $this->levy($node, 6);
    }
    private function levy(Unit $unit, int $amount) {
        $this->report .= "Налог для " . get_class($unit);
        $this->report .= ": $amount<br>";
        $this->due += $amount;
    }
    public function getReport() {
        return $this->report;
    }
    public function getTax() {
        return $this->due;
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
    public function bombardStrength(): int {
        $ret = 0;
        foreach ($this->units as $unit) {
            $ret += $unit->bombardStrength();
        }
        return $ret;
    }
}

class Cavalry extends CompositeUnit {

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
        if ($unit instanceof Cavalry) {
            throw new UnitException("Нельзя перемещать лошадь на бронетранспортере.");
        }
        parent::addUnit($unit);
    }
    public function bombardStrength(): int
    {
        return 0;
    }
}

echo "<pre>";

$main_army = new Army();
$main_army->addUnit(new Archer());
$main_army->addUnit(new LaserCannonUnit());
$main_army->addUnit(new Cavalry());

$sub_army = new Army();
$main_army->addUnit($sub_army);

$sub_army->addUnit(new LaserCannonUnit());
$sub_army->addUnit(new LaserCannonUnit());
$sub_army->addUnit(new LaserCannonUnit());

$textdump = new TextDumpArmyVisitor();
$main_army->accept($textdump);
print_r($textdump->getText());

echo "<br><br><br>";

$taxcollector = new TaxCollectionVisitor();
$main_army->accept($taxcollector);
print_r($taxcollector->getReport());
echo "Итого: ";
print_r($taxcollector->getTax());