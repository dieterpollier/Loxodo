<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App;


class Request
{
    protected $uri;
    protected $method;
    protected $params;
    protected $user;
    protected $flash;
    protected $language;



    public function __construct()
    {
        $this->language = DEFAULT_LANG;
        if(!defined('REQUEST_LANG')){
            define('REQUEST_LANG', $this->language);
        }
    }

    public function setUri($uri)
    {
        $this->uri = (string)$uri;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getParam($param)
    {
        if(!isset($this->params[$param])){
            return null;
        }
        return $this->params[$param];
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user)
    {
        $this->user =  $user;
    }

    /**
     * @return mixed
     */
    public function getFlash($name = "")
    {
        if($name !== ""){
            return isset($this->flash[$name])?  $this->flash[$name] : null;
        }
        return $this->flash;
    }

    /**
     * @param mixed $flash
     */
    public function setFlash($flash, $name = "")
    {
        if($name != $name){
            $this->flash = $name;
        }else{
            $this->flash = $flash;
        }
    }

    public function isGetRequest()
    {
        return $this->method == "GET";
    }


}