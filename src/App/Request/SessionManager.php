<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Request;


use Loxodo\App\Users;
use Loxodo\App\Users\User;

class SessionManager
{

    /**
     * If there is a user in the session return the user, otherwise create a user
     * @return \Loxodo\App\Users\User
     */
    public static function loadUser()
    {
        if(!isset($_SESSION['__user'])){
            $_SESSION['__user'] = new User();
        } elseif(is_a($_SESSION['__user'], 'Loxodo\App\Users\UserInterface') && $_SESSION['__user']->isLoggedIn()){
            $_SESSION['__user']->setLoggedIn();
        }
        return $_SESSION['__user'];
    }

    /**
     * Set the current user
     * @param \Loxodo\App\Users\User $user
     */
    public static function setUser(Users\User $user)
    {
        $_SESSION['__user'] = $user;
    }

    /**
     * Log the registered user out
     */
    public static function logout()
    {
        $_SESSION['__user']->logout();
    }

    /**
     * Provide data to the next request
     * @param $name
     * @param $value
     */
    public static function flash($name, $value)
    {
        $_SESSION['__flash'][$name] = $value;
    }

    /**
     * Retrieve data from sessionflash and set to empty array
     * @return Array
     */
    public static function cleanFlash()
    {
        if(!isset($_SESSION['__flash'])){
            $_SESSION['__flash'] = array();
        }
        $flash = $_SESSION['__flash'];
        $_SESSION['__flash'] = array();
        return $flash;
    }

}