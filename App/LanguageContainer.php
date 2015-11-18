<?php


namespace Loxodo\App;


class LanguageContainer
{
    protected $tags = array();

    public function get($slug, $language)
    {
        $slugArray = explode('.', $slug);
        $tag = $slugArray[count($slugArray) - 1];
        unset($slugArray[count($slugArray) - 1]);

        if (!isset($this->tags[$language])) {
            $this->tags[$language] = array();
        }
        $pointer = &$this->tags[$language];
        foreach ($slugArray as $elem) {
            if (isset($pointer[$elem])) {
                $pointer = &$pointer[$elem];
            } else {
                $pointer[$elem] = array();
                $pointer = &$pointer[$elem];
            }
        }

        if(isset($pointer[$tag])){
            return $pointer[$tag];
        } elseif(count($pointer) == 0){
            $pointer = $this->loadTranslations($language, $slugArray);
        }

        if(isset($pointer[$tag])){
            return $pointer[$tag];
        }
        return $slug;
    }

    protected function loadTranslations($language, &$slugArray)
    {
        $file = PROJECT_ROOT .'resources/languages/'. $language. '/'. implode('/', $slugArray). '.php';
        if(file_exists($file)){
            require_once $file;
            return $lang;
        }
        return array();
    }

}