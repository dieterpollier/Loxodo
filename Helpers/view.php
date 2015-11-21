<?php

use Loxodo\App\Request\CSRFProtector;

function view($view, $data = array())
{
    ob_start();
    extract($data);
    require  PROJECT_ROOT. VIEW_PATH . $view . '.php';
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function sysview($view, $data = array())
{
    ob_start();
    extract($data);
    if(file_exists(PROJECT_ROOT.VIEW_PATH.'errors/'.$view.'.php')){
        require PROJECT_ROOT.VIEW_PATH.'errors/'.$view.'.php';
    } else{
        require  LOXODO_BASE_PATH .'view/' . $view . '.php';
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function csrf()
{
    $csrfProtector = new CSRFProtector();
    return sysview('csrf', array('token' => $csrfProtector->createCsrf()));
}