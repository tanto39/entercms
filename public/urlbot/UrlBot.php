<?php

namespace UrlBot;

class UrlBotReload
{
    public $prefix = 'User';

    public static function reloadUrl($url)
    {


        $rest = substr(file_get_contents($url), 0, 8);
        return $rest;
    }
}
