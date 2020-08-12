<?php

class bangumi
{
    public static function delete_bangumi_from_id($id)
    {
        $all_bangumi = get_option("sinonbangumilist_savedbangumi");
        if ($all_bangumi==null) {
            return;
        }
        if ($all_bangumi[$id]!=null) {
            unset($all_bangumi[$id]);
        }
        return update_option("sinonbangumilist_savedbangumi", $all_bangumi);
    }
    public static function get_bangumi_by_id($id)
    {
        $bangumi=[];
        $all_bangumi = get_option("sinonbangumilist_savedbangumi");
        if ($all_bangumi!=null) {
            $bangumi = $all_bangumi[$id];
        }
        return $bangumi;
    }

    public static function update_bangumi_status($id, $status, $times, $progress)
    {
        $all_bangumi = get_option("sinonbangumilist_savedbangumi");
        if ($all_bangumi==null) {
            $all_bangumi=[];
        }
        $bangumi = $all_bangumi[$id];
        $bangumi['status']=$status;
        if ($times!=null) {
            $bangumi['times']=$times;
        }
        if ($progress!=null) {
            $bangumi['progress']=$progress;
        }
        
        $all_bangumi[$id]=$bangumi;
        return update_option("sinonbangumilist_savedbangumi", $all_bangumi);
    }

    public static function add_or_update_bangumi($id, $url, $img, $name, $name_cn, $date, $count, $title)
    {
        $all_bangumi = get_option("sinonbangumilist_savedbangumi");
        if ($all_bangumi==null) {
            $all_bangumi=[];
        }
        $bangumi = $all_bangumi[$id];
        //copy bangumi
        $old_bangumi = array_merge([], $bangumi);
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
        if ($old_bangumi!=null) {
            //check if not change
            if ($old_bangumi == $bangumi) {
                return true;
            }
        }

        return update_option("sinonbangumilist_savedbangumi", $all_bangumi);
    }
}
