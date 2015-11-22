<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Request;


use Loxodo\App\Request\Request;
use Loxodo\App\Request\SessionManager;

class RequestBuilder
{

    /**
     * Create a Request-object with the data from the http-request and the session
     * @return Request
     */
    public function build()
    {
        $request = new Request();
        $uri = $this->cleanUri($_SERVER['REQUEST_URI']);
        $request->setMethod($this->deriveRequestMethod());
        $filteredUriLang = $this->findLanguageInUri($uri);
        $request->setUri($filteredUriLang->uri);
        $request->setLanguage($filteredUriLang->language);
        $request->setUser(SessionManager::loadUser());
        $request->setFlash(SessionManager::cleanFlash());
        $request->setParams($_POST);
        return $request;
    }

    /**
     * Removes unnecessary '/' and the getparams from the uri
     * @param $uri
     * @return string
     */
    protected function cleanUri($uri)
    {
        if(strpos($uri, '/') === 0){
            $uri = substr($uri,1);
        }
        if($uri[strlen($uri)-1] == "/"){
            $uri = substr($uri, 0, strlen($uri)-1);
        }
        $getParamStart = strpos($uri, '?');
        if($getParamStart !== false){
            $uri = substr($uri, 0, $getParamStart);
        }
        return (string)urldecode($uri);
    }

    /**
     * Get the http-requestmethod. If a htmlform with '_method' was submitted, this
     * will be used as the http-requestmethod
     * @return string
     */
    protected function deriveRequestMethod()
    {
        if (isset($_POST['_method']) && in_array(strtolower($_POST['_method']), array('get', 'post', 'delete', 'put'))){
            return strtoupper($_POST['_method']);
        }
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Search for a language in the uri. Returns a stdClass with the filtered
     * uri and the language. If there is no language found, the defaultlanguage will
     * be returned
     * @param $uri
     * @return \stdClass
     */
    protected function findLanguageInUri($uri)
    {
        $response = new \stdClass();
        $response->uri = $uri;
        $response->language = DEFAULT_LANG;

        if(strlen($uri) >= 2){
            $abbr = strtolower(substr($uri, 0, 3));
            if(in_array($abbr, array('nl/', 'en/'))){
                $response->language = str_replace('/', '', $abbr);
                $response->uri = substr($uri, 3);
            }
        }
        return $response;
    }



}