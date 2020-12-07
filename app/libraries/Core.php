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
                //require_once '../app/controllers/' . $this->currentControllerFile . '.php';
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
                    $this->currentMethod = 'index';
                    $this->params = [404];
                }
            } else {
                $this->currentControllerFile = 'Errors';
                $this->currentMethod = 'index';
                $this->params = [404];
            }
        }

        //require_once '../app/controllers/' . $this->currentControllerFile . '.php';
        $this->loadControllerSpecificConfig($this->currentControllerFile);
        $this->currentController = new $this->currentControllerFile;
        setCurrentAction($this->currentMethod);
        setCurrentController($this->currentControllerFile);
        /*try {
            $reflection = new ReflectionMethod($this->currentController, $this->currentMethod);
            if ($reflection->getNumberOfRequiredParameters() > count($this->params)) {
                call_user_func_array([new Errors(), 'index'], [404]);
                return;
            }
            foreach($reflection->getParameters() AS $key => $arg)
            {
                if (empty($this->params) && $arg->allowsNull()) {
                    $this->params[$key] = null;
                    break;
                }
                if ($arg->getType()->getName() !== gettype($this->params[$key]?? [])){
                    call_user_func_array([new Errors(), 'index'], [404]);
                    return;
                }
            }
        } catch (ReflectionException $e) {
        }*/
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

    public function loadControllerSpecificConfig(string $controller)
    {
        $config_path =  dirname(__DIR__) . '/config/' . "$controller". "config.php";
        if (file_exists($config_path))
        require_once $config_path;
    }
}
