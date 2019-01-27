<?php

class SanitiseHTMLDisplay
{
    public static function sanitiseInput($str)
    {
        return htmlentities($str);
    }
}
