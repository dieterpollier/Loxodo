<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

function redirect($location, $statuscode = "303")
{
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