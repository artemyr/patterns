<?php
abstract class Question {
    protected $prompt;
    protected $marker;
    public function __construct(string $prompt, Marker $marker)
    {
        $this->prompt = $prompt;
        $this->marker = $marker;
    }
    public function mark(string $response): bool {
        return $this->marker->mark($response);
    }
}
class TextQuestion extends Question {}
class AVQuestion extends Question {}
class MarkParse {
    public function evaluate() {
        return 1;
    }
}
abstract class Marker {
    protected $test;
    public function __construct(string $test)
    {
        $this->test = $test;
    }
    abstract public function mark(string $response): bool;
}
class MarkLogicMarker extends Marker {
    private $engine;
    public function __construct(string $test)
    {
        parent::__construct($test);
        $this->engine = new MarkParse($test);
    }
    public function mark(string $response): bool
    {
        return $this->engine->evaluate($response);
    }
}
class MatchMarker extends Marker {
    public function mark(string $response): bool
    {
        return ($this->test == $response);
    }
}
class RegexpMarker extends Marker {
    public function mark(string $response): bool
    {
        return (preg_match("$this->test", $response) === 1);
    }
}

echo "<pre>";
$markers = [
    new RegexpMarker("/П.+ть/"),
    new MatchMarker("Пять"),
    new MarkLogicMarker('$input equals "Пять"'),
];
foreach ($markers as $marker) {
    print_r(get_class($marker) . "<br>");
    $question = new TextQuestion("Сколько лучей у звезды?", $marker);
    foreach (["Пять", "Четыре"] as $response) {
        print_r(" Ответ: $response: ");
        if ($question->mark($response)) {
            echo "Правильно!<br>";
        } else {
            echo "Неверно!<br>";
        }
    }
}