<?php

class view_controller
{
    public static function bangumi_list()
    {
        include_once(ROOT_PATH."/views/bangumi-list-view.php");
    }

    public static function bangumi_options()
    {
        include_once(ROOT_PATH."/views/bangumi-options-view.php");
    }

    public static function bangumi_new()
    {
        include_once(ROOT_PATH."/views/bangumi-new-view.php");
    }
}
