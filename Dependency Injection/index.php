<?php
class ObjectAssembler {
    private $components = [];
    public function __construct(string $conf) {
        $this->configure($conf);
    }
    private function configure(string $conf) {
        $data = simplexml_load_file($conf);
        foreach ($data->class as $class) {
            $args = [];
            $name = (string)$class['name'];
            foreach ($class->arg as $arg) {
                $argclass = (string)$arg['inst'];
                $args[(int)$arg['num']] = $argclass;
            }
            ksort($args);
            $this->components[$name] = function () use ($name, $args) {
                $expendedargs = [];
                foreach ($args as $arg) {
                    $expendedargs[] = new $arg();
                }
                $rclass = new \ReflectionClass($name);
                return $rclass->newInstanceArgs($expendedargs);
            };
        }
    }
    public function getComponent(string $class) {
        if (!isset($this->components[$class])) {
            throw new \Exception("Неизвестный компонент '$class'");
        }
        return $this->components[$class]();
    }
}

$assembler = new ObjectAssembler("objects.xml");
$apptmarker = $assembler->getComponent("AppointmentMarker2");
$out =  $apptmarker->makeAppointmant();
print_r($out);