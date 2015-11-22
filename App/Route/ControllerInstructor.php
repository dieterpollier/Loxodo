<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Route;


use ReflectionClass;

class ControllerInstructor
{

    public function getMethodToCall($className, $httpMethod, $parameters)
    {
        $class = new ReflectionClass($className);
         if (!$this->setFunctionFromUri($class, $parameters)) {
            $this->setDefaultRestFunction();
        }

    }

    protected function setFunctionFromUri(ReflectionClass $class, $controllerParameters)
    {
        foreach ($controllerParameters as $key => $param) {
            if ($class->hasMethod($param)) {
                $this->function = $param;
                unset($this->params[$key]);
                return true;
            }
        }
        return false;
    }

    protected function setDefaultRestFunction()
    {
        if ($this->method === "GET") {
            $function = $this->function = count($this->controllerParams) > 0 ? "view" : "index";
            $class = new ReflectionClass($this->getController());
            if($function == "view"){
                $method = $class->hasMethod('view') ? $class->getMethod('view') : null;
                if(is_null($method) || !$method->isPublic()){
                    $this->function = "edit";
                }
            }
        }else{
            $defaults = array('PUT' => 'update', 'POST' => 'store', 'PATCH' => 'update', 'DELETE' => 'delete');
            $this->function =  isset($defaults[$this->method]) ? $defaults[$this->method] : '';
        }
    }

}