<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Request;


use Loxodo\App\Users\Guard;
use Loxodo\App\Request\CSRFProtector;
use Loxodo\App\Request\Request;
use Loxodo\App\Route\InjectionContainer;
use Loxodo\App\Route\Route;

class Dispatcher
{

    public function dispatch(Request $request, InjectionContainer $injections)
    {
        $guard = $this->loadGuard($request->getUser());
        $route = new Route($request->getMethod(), $request->getUri(), $guard, $injections);
        if(!$route->hasAccess()){
            $redirect = $guard->getHttpRedirect($route->getFolders());
            if(!empty($redirect)){
                redirect($redirect);
            }
            showError(403);
        }

        //Load the page
        if($route->getMethod() !== "GET" && $this->isCsrfViolation($guard, $route->getDir())){
            redirect($_SERVER['HTTP_REFERER']);
        } elseif($route->isValidRoute()){
            $class = $route->getController();
            $function = $route->getFunction();
            $controller = new $class();
            call_user_func_array(array(&$controller, $function), $route->getInjections());
        } else{
            showError(404);
        }

    }

    protected function loadGuard($user)
    {
        $guardConfiguration = array();
        if(file_exists(PROJECT_ROOT.CONFIG_PATH.'accessmapper.json')){
            $guardConfiguration = json_decode(file_get_contents(PROJECT_ROOT.CONFIG_PATH.'accessmapper.json'));
        }
        return new Guard($user, $guardConfiguration);
    }

    protected function isCsrfViolation(Guard $guard, $folder)
    {
        $csrfProtection = new CSRFProtector();
        return $guard->hasCsrfProtection($folder) && !$csrfProtection->validCSRF();
    }

}