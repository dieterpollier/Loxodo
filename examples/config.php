<?php


/****************
 *
 * FRAMEWORK
 *
 ***************/

//APPLICATION
define('APPLICATION_NAMESPACE_PREFIX', "Demo");
define('PROJECT_ROOT', str_replace('public/', '', $_SERVER['DOCUMENT_ROOT'].'/'));
define('DEFAULT_LANG', 'nl');
define('CSRF_PROTECTION', true);

//PATHS
define('VIEW_PATH', 'resources/views/');
define('LANG_PATH', 'resources/languages/');
define('CONFIG_PATH', 'application/config/');
define('CONTROLLER_PATH', 'application/Controllers');

//CONTROLLERS
define('DEFAULT_CONTROLLER', 'Home');
define('CONTROLLER_SUFFIX', 'Controller');

//USERS
define('DEFAULT_PROFILE', 'my_user');
define('SESSION_LIFETIME', 100000);
define('LOGIN_SESSION_LIFETIME', 3000);


/****************
 *
 * APPLICATION
 *
 ***************/
