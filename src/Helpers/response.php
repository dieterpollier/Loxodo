<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

function redirect($location, $statuscode = "303")
{
    if(REDIRECT_FROM_ROOT && strpos($location, 'http') !== 0){
        if(!preg_match('/^([\/])/', $location)){
            $location = '/'.$location;
        }
        if(REDIRECT_ADD_LANG && !preg_match('/^([\/]{0,1})(nl|NL|fr|FR|en|EN)/', $location)){
            $location = '/'.REQUEST_LANG. $location;
        }

    }

    //http_response_code($statuscode);
    header('Location: '.$location, true, $statuscode);
    exit();
}

function showError($statuscode)
{
    if(in_array($statuscode, array('404', '403', '500'))){
        //http_response_code($statuscode);
        print sysview('error_'.$statuscode);
        exit();
    }

}