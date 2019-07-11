<?php
namespace Core\Controller;

class Controller
{

    private $twig;

    private $app;

    protected function render(string $view, array $variables = [])
    {

        $variables["debugTime"] = $this->getApp()->getDebugTime();
        echo $this->getTwig()->render(
            $view . '.twig',
            $variables
        );
    }

    private function getTwig()
    {
        if (is_null($this->twig)) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(dirname(__dir__)) . '/views/');
            $this->twig = new \Twig\Environment($loader);
            if (!empty($_SESSION['auth'])) {
                $this->twig->addGlobal("auth", $_SESSION['auth']);
            }

            if (!empty($_SESSION['token'])) {
                $this->twig->addGlobal("token", $_SESSION['token']);
            }

            if (!empty($_SESSION['success'])) {
                $this->twig->addGlobal("success", $_SESSION['success']);
            }

            if (!empty($_SESSION['error'])) {
                $this->twig->addGlobal("error", $_SESSION['error']);
            }
        }
        return $this->twig;
    }

    protected function getApp()
    {
        if (is_null($this->app)) {
            $this->app = \App\App::getInstance();
        }
        return $this->app;
    }

    protected function generateUrl(string $routeName, array $params = []): String
    {
        return $this->getApp()->getRouter()->url($routeName, $params);
    }

    protected function loadModel(string $nameTable): void
    {
        $this->$nameTable = $this->getApp()->getTable($nameTable);
    }

    protected function connected($user)
    {
        $_SESSION['auth'] = $user;
        return $_SESSION['auth'];
    }
}
