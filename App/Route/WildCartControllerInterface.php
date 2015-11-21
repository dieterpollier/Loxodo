<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Route;


use Loxodo\App\Request\Request;

interface WildCartControllerInterface
{

    public function index(Request $request, $path = "");

}