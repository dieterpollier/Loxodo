<?php

global $langContainer;
$langContainer = new \Loxodo\App\LanguageContainer();

function lang($slug, $language = REQUEST_LANG)
{
    global $langContainer;
    return $langContainer->get($slug, $language);
}