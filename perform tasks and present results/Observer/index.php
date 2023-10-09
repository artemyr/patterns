<?php
interface Observable {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}
interface Observer {
    public function update(Observable $observable);
}
class Login implements Observable {
    private $observers = [];
    private $storage;
    private $status = [];
    const LOGIN_USER_UNKNOWN = 1;
    const LOGIN_WRONG_PASS = 2;
    const LOGIN_ACCESS = 3;
    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }
    public function detach(Observer $observer)
    {
        $this->observers = array_filter($this->observers, function ($a) use ($observer) {
            return (! ($a === $observer));
        });
    }
    public function notify()
    {
        foreach ($this->observers as $obs) {
            $obs->update($this);
        }
    }
    public function handleLogin(string $user, string $pass, string $ip): bool {
        $isinvalid = false;
        switch (rand(1,3)) {
            case 1:
                $this->setStatus(self::LOGIN_ACCESS, $user, $ip);
                $isinvalid = true;
                break;
            case 2:
                $this->setStatus(self::LOGIN_WRONG_PASS, $user, $ip);
                $isinvalid = false;
                break;
            case 3:
                $this->setStatus(self::LOGIN_USER_UNKNOWN, $user, $ip);
                $isinvalid = false;
                break;
        }
        $this->notify();
        return $isinvalid;
    }
    private function setStatus(int $status, string $user, string $ip) {
        $this->status = [$status, $user, $ip];
    }
    public function getStatus(): array
    {
        return $this->status;
    }
}
abstract class LoginObserver implements Observer {
    private $login;
    public function __construct(Login $login)
    {
        $this->login = $login;
        $login->attach($this);
    }
    public function update(Observable $observable)
    {
        if ($observable === $this->login) {
            $this->doUpdate($observable);
        }
    }
    abstract public function doUpdate(Login $login);
}
class SecurityMonitor extends LoginObserver {
    public function doUpdate(Login $login)
    {
        $status = $login->getStatus();
        if ($status[0] == Login::LOGIN_WRONG_PASS) {
            // послать сообщение по электронной почте
            // системному администратору
            print_r(__CLASS__. ": Отправка почты системному адмнистратору <br>");
        }
    }
}
class GeneralLogger extends LoginObserver {
    public function doUpdate(Login $login)
    {
        $status = $login->getStatus();
        // записать данные регистрации в системный дурнал
        print_r(__CLASS__. ": Регистрация в системном журнале <br>");
    }
}
class PartnershipTool extends LoginObserver {
    public function doUpdate(Login $login)
    {
        $status = $login->getStatus();
        // отпарвить cookie файл, если адрес соответствуе списку
        print_r(__CLASS__. ": Отправка cookie-файла, если адрес соответствуе списку <br>");
    }
}

$login = new Login();
new SecurityMonitor($login);
new GeneralLogger($login);
new PartnershipTool($login);
$login->handleLogin("login", "pass", "155.155.155.155");