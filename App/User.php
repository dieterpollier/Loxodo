<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace App;

/**
 * An instance of the visitor for the website
 * Class User
 * @package App
 */
class User
{
    protected $id, $account, $profile, $timestamp, $lastRequest;

    /**
     * User constructor.
     * @param $id
     * @param $profile
     * @param $timestamp
     */
    public function __construct($id = 0, $account = "", $profile = "")
    {
        $this->id = $id;
        $this->account = $account;
        if(empty($profile) && defined('DEFAULT_PROFILE')){
            $this->profile = DEFAULT_PROFILE;
        }
        $this->profile = $profile;
        $this->timestamp = time();
        $this->lastRequest = time();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Returns the profile
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * The timestamp when the user logged in.
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Is the user logged in?
     * @return bool
     */
    public function isLoggedIn()
    {
        if(time() < $this->lastRequest + LOGIN_SESSION_LIFETIME){
            return true;
        }
        $this->destroy();
        return false;
    }

    /**
     * Updates the lastrequest for the user in the session. If the user is not
     * logged in, the timestamp will be updated.
     *
     */
    public function setLoggedIn()
    {
        if(!$this->isLoggedIn()){
            $this->timestamp = time();
        }
        $this->lastRequest = time();
    }

    public function logout()
    {
        $this->destroy();
    }

    public function destroy()
    {
        $this->lastRequest = 0;
        $this->id = 0;
        $this->profile = defined("DEFAULT_PROFILE") ? DEFAULT_PROFILE : "";
        $this->account = "";
    }
}