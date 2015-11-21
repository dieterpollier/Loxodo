<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Request;


class CSRFProtector
{

    public function createCsrf()
    {
        $token = new \stdClass();
        $token->expires = time()+ 900;
        $token->csrfToken = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30);
        $_SESSION['csrf_tokens'][] = $token;
        return $token->csrfToken;
    }

    public function validCSRF()
    {
        foreach($_SESSION['csrf_tokens'] as $key => $token){
            if(time() > $token->expires){
                unset($_SESSION['csrf_tokens'][$key]);
            }
            elseif($token->csrfToken == $_POST['_csrf']){
                unset($_SESSION['csrf_tokens'][$key]);
                return true;
            }
        }
        return false;
    }

}