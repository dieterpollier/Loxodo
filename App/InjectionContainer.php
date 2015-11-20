<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App;


/**
 * Class InjectionContainer
 * @package Loxodonta\App
 */
class InjectionContainer
{
    /**
     * @var Request
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