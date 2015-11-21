<?php

namespace Loxodo\App;


use Loxodo\App\Request\Dispatcher;
use Loxodo\App\Request\Request;
use Loxodo\App\Request\RequestBuilder;
use Loxodo\App\Route\InjectionContainer;

class App
{

    protected $injections = array();
    protected $request;
    protected $configPath;

    /**
     * App constructor.
     */
    public function __construct($configPath)
    {
        $this->configPath = $configPath;
    }


    public function run()
    {
        $this->boot();
        $this->handleRequest(new InjectionContainer());
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    protected function initEnvironment()
    {
        include_once $this->configPath;
        $dotEnv = new \Dotenv\Dotenv(PROJECT_ROOT);
        $dotEnv->load();
        $absApplicationPath = realpath(__DIR__);
        define('LOXODO_BASE_PATH', substr($absApplicationPath, 0, strrpos($absApplicationPath, 'App')));
    }

    protected function setErrorHandling()
    {
        register_shutdown_function(array($this, 'close'));
        if(getenv('SHOW_ERRORS') === "true"){
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else{
            error_reporting(E_ERROR | E_PARSE | E_WARNING);
            ini_set('display_errors', 0);
            ob_start();
        }
    }

    protected function startSession()
    {
        session_set_cookie_params(SESSION_LIFETIME);
        session_start();
    }

    protected function loadHelpers()
    {
        $helpersPath = LOXODO_BASE_PATH .'Helpers/';

        include_once $helpersPath.'view.php';
        include_once $helpersPath.'response.php';
        include_once $helpersPath.'language.php';
    }

    public function handleRequest(InjectionContainer $injectionContainer = null)
    {
        $dispatcher = new Dispatcher();
        if(is_null($injectionContainer)){
            $injectionContainer = new InjectionContainer();
        }
        $injectionContainer->setRequest($this->request);
        $dispatcher->dispatch($this->request, $injectionContainer);
    }

    public function close()
    {
        if(getenv('SHOW_ERRORS') !== "true"){
            $error = error_get_last();
            if($error && !in_array($error['type'], array(E_NOTICE, E_DEPRECATED))){
                ob_end_clean();
                showError(500);
            } else {
                ob_end_flush();
            }
        }
    }

    public function boot()
    {
        $this->initEnvironment();
        $this->setErrorHandling();
        $this->startSession();
        $this->loadHelpers();
        $requestBuilder = new RequestBuilder();
        $this->request = $requestBuilder->build();
    }

}