<?php

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;

class App
{

    protected $injections = array();
    protected $request;

    public function run()
    {
        $this->boot();
        $this->initDatabase();
        $this->handleRequest();
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
        $dotEnv = new \Dotenv\Dotenv('../');
        $dotEnv->load();
        include_once '../application/config/config.php';
    }

    protected function initDatabase()
    {
        $capsule = new Capsule;

        $capsule->addConnection(array(
            'driver'    => 'mysql',
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_NAME'),
            'username'  => getenv('DB_USER'),
            'password'  => getenv('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
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
        include_once PROJECT_ROOT.'system/Helpers/view.php';
        include_once PROJECT_ROOT.'system/Helpers/response.php';
        include_once PROJECT_ROOT.'system/Helpers/language.php';
    }

    public function handleRequest()
    {
        $dispatcher = new Dispatcher();
        $this->addInjection('request', $this->request);
        $dispatcher->dispatch($this->request, $this->injections);
    }

    public function addInjection($name, $value)
    {
        $this->injections[$name] = $value;
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