<?php
abstract class Command {
    abstract public function execute(CommandContext $context): bool;
}
class CommandContext {
    private $params = [];
    private $error = "";
    public function __construct() {
        $this->params = $_REQUEST;
    }
    public function addParam(string $key, $val) {
        $this->params[$key] = $val;
    }
    public function get(string $key): string
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return "";
    }
    public function setError($error)
    {
        $this->error = $error;
    }
    public function getError(): string
    {
        return $this->error;
    }
}
class CommandNotFoundException extends \Exception {}
class CommandFactory {
    private static $dir = 'commands';
    public static function getCommand(string $action = 'Default'): Command {
        if (preg_match('/\W/', $action)) {
            throw new \Exception("Недопустимые символы в команде");
        }
        $class = UCFirst(strtolower($action)) . "Command";
        $file = self::$dir . DIRECTORY_SEPARATOR . "{$class}.php";
        if (!file_exists($file)) {
            throw new CommandNotFoundException("Файл '$file' не найден");
        }
        require_once $file;
        if (!class_exists($class)) {
            throw new CommandNotFoundException("Класс '$class' не обнаружен");
        }
        return new $class();
    }
}
class Controller {
    private $context;
    public function __construct() {
        $this->context = new CommandContext();
    }
    public function getContext(): CommandContext {
        return $this->context;
    }
    public function process() {
        $action = $this->context->get('action');
        $action = (is_null($action)) ? "default" : $action;
        $cmd = CommandFactory::getCommand($action);
        if (!$cmd->execute($this->context)) {
            // обработать неудачный исход
            echo $this->context->getError();
        } else {
            // обработать удачный исход: отобразить представление
            echo "Вы авторизовались<br>";
        }
    }
}

?>
    <form action="" method="post">
        <p>Логин bob</p>
        <p>Пароль pass</p>
        <input type="text" name="action" value="login">
        <input type="text" name="username">
        <input type="text" name="pass">
        <input type="submit">
    </form>
<?php
if (empty($_POST['username']) || empty($_POST['pass'])) return;

$controller = new Controller();
$context = $controller->getContext();

$context->addParam('action', $_POST['action']);
$context->addParam('username', $_POST['username']);
$context->addParam('pass', $_POST['pass']);

$controller->process();

//print_r( $context->getError() );