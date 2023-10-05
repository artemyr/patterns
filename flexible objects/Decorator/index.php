<?php
abstract class Tile {
    abstract public function getWealthFactor(): int;
}
abstract class TileDecorator extends Tile {
    protected $tile;
    public function __construct(Tile $tile) {
        $this->tile = $tile;
    }
}
class DiamondDecorator extends TileDecorator {
    public function getWealthFactor(): int
    {
        return $this->tile->getWealthFactor() + 2;
    }
}
class PollutedDecorator extends TileDecorator {
    public function getWealthFactor(): int
    {
        return $this->tile->getWealthFactor() - 4;
    }
}
class Plains extends Tile {
    private $wealthfactor = 2;

    public function getWealthFactor(): int
    {
        return $this->wealthfactor;
    }
}
class DiamondPlains extends Plains {
    public function getWealthFactor(): int
    {
        return parent::getWealthFactor() + 2;
    }
}
class PollutedPlains extends Plains {
    public function getWealthFactor(): int
    {
        return parent::getWealthFactor() - 4;
    }
}

echo "<pre>";
echo "до<br>";
$tile = new PollutedPlains();
print_r($tile->getWealthFactor());

echo "<br>после<br>";

echo "Plains<br>";

$tile = new Plains();
print_r($tile->getWealthFactor());

echo "<br>Plains -> DiamondDecorator<br>";

$tile = new DiamondDecorator(new Plains());
print_r($tile->getWealthFactor());

echo "<br>Plains -> DiamondDecorator -> PollutedDecorator<br>";

$tile = new PollutedDecorator(new DiamondDecorator(new Plains()));
print_r($tile->getWealthFactor());


echo "<br><br>-----------------------------------<br><br>";

class RequestHelper {

}
abstract class ProcessRequest {
    abstract public function process(RequestHelper $req);
}
class MainProcess extends ProcessRequest {
    public function process(RequestHelper $req)
    {
        print_r(__CLASS__. ": выполнение запроса<br>");
    }
}
abstract class DecorateProcess extends ProcessRequest {
    protected $proccessrequest;
    public function __construct(ProcessRequest $pr) {
        $this->proccessrequest = $pr;
    }
}
class LogRequest extends DecorateProcess {

    public function process(RequestHelper $req)
    {
        print_r(__CLASS__. ": регистрация запроса<br>");
        $this->proccessrequest->process($req);
    }
}
class AuthenticateRequest extends DecorateProcess {

    public function process(RequestHelper $req)
    {
        print_r(__CLASS__. ": аутентификация запроса запроса<br>");
        $this->proccessrequest->process($req);
    }
}
class StructureRequest extends DecorateProcess {

    public function process(RequestHelper $req)
    {
        print_r(__CLASS__. ": формирование данных запроса<br>");
        $this->proccessrequest->process($req);
    }
}
$process = new AuthenticateRequest(new StructureRequest(new LogRequest(new MainProcess())));
$process->process(new RequestHelper());