<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App;


interface UserInterface
{

    public function __construct($id = 0, $account = "", $profile = "");

    public function getId();
    public function getAccount();
    public function getProfile();
    public function isLoggedIn();
    public function setLoggedIn();
    public function logout();
    public function destroy();

}