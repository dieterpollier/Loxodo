<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Route;
use Loxodo\App\Request\Request;


/**
 * Class InjectionContainer
 * @package Loxodonta\App
 */
class InjectionContainer
{
    /**
     * @var \Loxodo\App\Request\Request
     */
    public $request = null;


    /**
     * @param null $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

}