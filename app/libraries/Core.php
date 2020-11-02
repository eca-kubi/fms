<?php

/*
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */

class Core
{
    protected string $currentControllerFile = 'Pages';
    protected object $currentController;
    protected string $currentMethod = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->getUrl();
        if (isset($url['0'])) {
            $this->currentControllerFile = str_replace(['-', '_'], '', ucwords($url['0']));
            unset($url[0]);
            if (file_exists('../app/controllers/' . $this->currentControllerFile . '.php')) {
                require_once '../app/controllers/' . $this->currentControllerFile . '.php';
                $this->currentController = new $this->currentControllerFile;
                if (isset($url[1])) {
                    $this->currentMethod = str_replace(['-', '_'], '', $url[1]);
                    unset($url[1]);
                }
                if (method_exists($this->currentController, $this->currentMethod)) {
                    $this->params = $url ? array_values($url) : [];
                    $getParams = fetchGetParams();
                    if ($this->currentMethod === 'login') {
                        $this->params = [implode('/', $this->params). ($getParams? "?$getParams" : "")];
                    }
                } else {
                    $this->currentControllerFile = 'Errors';
                    $this->params = [404];
                }
            } else {
                $this->currentControllerFile = 'Errors';
                $this->params = [404];
            }
        }

        require_once '../app/controllers/' . $this->currentControllerFile . '.php';
        $this->currentController = new $this->currentControllerFile;
        setCurrentAction($this->currentMethod);
        setCurrentController($this->currentControllerFile);
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
