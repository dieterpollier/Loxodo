<?php


namespace Loxodo\App\Users;
use Loxodo\App\Users;
use Loxodo\App\Users\User;

/**
 * Implements the security-settings from the config/accessmapper.json to the uri
 *
 * Class Guard
 * @package App
 */
class Guard
{

    /**
     * @var \Loxodo\App\Users\User
     */
    protected $user;
    protected $guarded = array();

    /**
     * Guard constructor.
     * @param $user
     */
    public function __construct(Users\User $user, $guarded)
    {
        $this->user = $user;
        foreach($guarded as $map){
            $this->guarded[$this->convertDirectory($map->directory)] = $map;
        }
    }

    /**
     * Is the Folder registered in the accessmapper.
     *
     * @param $directory
     * @return bool
     */
    public function isGuarded($directory)
    {
        return isset($this->guarded[$this->convertDirectory($directory)]);
    }

    /**
     * Is there a redirect for the given user?
     *
     * @param $directory
     * @return bool
     */
    public function hasRedirect($directory){
        if($this->isGuarded($directory) && $this->user->isLoggedIn()){
            $map = $this->guarded[$this->convertDirectory($directory)];
            $profile = $this->user->getProfile();
            return isset($map->profiles->$profile);
        }
        return false;
    }

    public function getHttpRedirect($directory)
    {
        $dir = $this->convertDirectory($directory);
        if($this->isGuarded($dir) && !empty($this->guarded[$dir]->redirect)){
            return $this->guarded[$dir]->redirect;
        }
        return '';
    }

    /**
     * Get the destinationdirectory for the given user and the directory.
     *
     * @param $directory
     * @return string
     */
    public function getDestination($directory)
    {
        if(isset($this->guarded[$this->convertDirectory($directory)])){
            $map = $this->guarded[$this->convertDirectory($directory)];
            $profile = $this->user->getProfile();
            if(isset($map->profiles->$profile)){
                return $map->profiles->$profile;
            }
        }
        return $directory;
    }

    public function getPortalController($directory)
    {
        if(isset($this->guarded[$this->convertDirectory($directory)])){
            $map = $this->guarded[$this->convertDirectory($directory)];
            return isset($map->portal) ? $map->portal : '';
        }
        return '';
    }

    /**
     * Is the given folder CSRF-protected following the accessmapper?
     * By default a folder returns the setting from the config.
     * @param $directory
     * return Bool
     */
    public function hasCsrfProtection($directory)
    {
        $folder = $this->convertDirectory($directory);
        if($this->isGuarded($folder)){
            return isset($this->guarded[$folder]->csrf) &&  $this->guarded[$folder]->csrf === true;
        }
        return CSRF_PROTECTION;

    }

    /**
     * Return a array-friendly string
     * @param $directory
     * @return string
     */
    protected function convertDirectory($directory)
    {
        return (string)str_replace('/', '__', $directory);
    }

}