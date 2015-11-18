<?php

namespace Loxodo\App;


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
    }

    protected function setErrorHandling()
    {
        register_shutdown_function(array($this, 'close'));
        if(getenv('SHOW_ERRORS') === "true"){
            error_reporting(E_ALL);
        } else{
            error_reporting(0);
        }
    }

    protected function startSession()
    {
        session_set_cookie_params(SESSION_LIFETIME);
        session_start();
    }

    protected function loadHelpers()
    {
        $absApplicationPath = realpath(__DIR__);
        $helpersPath = substr($absApplicationPath, 0, strrpos($absApplicationPath, 'App')).'Helpers/';

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
        $error = error_get_last();
        if($error && getenv('SHOW_ERRORS') !== "true"){
            showError(500);
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