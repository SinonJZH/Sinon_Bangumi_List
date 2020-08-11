<?php

class bangumi
{
    public static function add_or_update_bangumi($id, $url, $img, $name, $name_cn, $date, $count, $title)
    {
        $all_bangumi = get_option("sinonbangumilist_savedbangumi");
        if ($all_bangumi==null) {
            $all_bangumi=[];
        }
        $bangumi = $all_bangumi[$id];
        if ($bangumi==null) {
            $bangumi = [];
        }
        if ($id!=null) {
            $bangumi['id']=$id;
        }
        if ($url!=null) {
            $bangumi['url']=$url;
        }
        if ($img!=null) {
            $bangumi['img']=$img;
        }
        if ($name!=null) {
            $bangumi['name']=$name;
        }
        if ($name_cn!=null) {
            $bangumi['name_cn']=$name_cn;
        }
        if ($date!=null) {
            $bangumi['date']=$date;
        }
        if ($count!=null) {
            $bangumi['count']=$count;
        }
        if ($title!=null) {
            $bangumi['title']=$title;
        }
        $all_bangumi[$id]=$bangumi;
        return update_option("sinonbangumilist_savedbangumi", $all_bangumi);
    }
}
