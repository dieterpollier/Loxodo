<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App;


use ReflectionClass;
use ReflectionParameter;

class Injector
{

    protected $injectionContainer = null;
    protected $countSystemInjections = 0;
    protected $countUriInjections = 0;
    protected $containerReflection = array();

    /**
     * Injector constructor.
     * @param null $injectionContainer
     */
    public function __construct($injectionContainer)
    {
        $this->injectionContainer = $injectionContainer;
        $class = get_class($injectionContainer);
        $this->containerReflection = new ReflectionClass($class);
    }

    /**
     * @return int
     */
    public function getCountUriInjections()
    {
        return $this->countUriInjections;
    }

    /**
     * @return int
     */
    public function getCountSystemInjections()
    {
        return $this->countSystemInjections;
    }



    public function getControllerParameters($controller, $function, $urlParameters)
    {
        $this->countSystemInjections = $this->countUriInjections = 0;
        $injections = array();
        if(!empty($controller) && !empty($function)){
            $class = new ReflectionClass($controller);

            if ($class->hasMethod($function)) {
                $method = $class->getMethod($function);
                $parameters = $method->getParameters();
                $keys = array_keys($urlParameters);
                foreach($parameters as $index => $parameter){
                    $urlValue = isset($keys[$this->countUriInjections]) && isset($urlParameters[$keys[$this->countUriInjections]]) ? $urlParameters[$keys[$this->countUriInjections]] : null;
                    $injections[$index] = $this->injectParameter($parameter, $urlValue);
                }
            }
        }
        return $injections;
    }


    protected function injectParameter(ReflectionParameter $parameter, $urlParameter)
    {
        $name = $parameter->getName();
        if($this->containerReflection->hasProperty($parameter->getName())){
            $this->countSystemInjections++;
            return $this->injectionContainer->$name;
        } elseif($this->containerReflection->hasMethod($name)){
            if(empty($urlParameter) && $parameter->isDefaultValueAvailable()){
                return call_user_func_array(array($this->injectionContainer, $name), array($parameter->getDefaultValue()));
            } elseif(!empty($urlParameter)){
                $this->countUriInjections++;
                return call_user_func_array(array($this->injectionContainer, $name), array($urlParameter));
            }
        } elseif(!empty($urlParameter)){
            $this->countUriInjections++;
            return $urlParameter;
        } elseif($parameter->isOptional()){
            return $parameter->getDefaultValue();
        }
        return null;
    }

}