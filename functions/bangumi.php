<?php
/**
* Access Wordpress database to save bangumi
*
*/
class bangumi
{
    /**
    * Delete bangumi by id
    *
    * @access public
    * @param mixed $id Id of bangumi in bangumi.tv
    * @return boolean Whether bangumi has been deleted or not.
    */
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

    /**
    * Get bangumi by id
    *
    * @access public
    * @param mixed $id Id of bangumi in bangumi.tv
    * @return array bangumi
    */
    public static function get_bangumi_by_id($id)
    {
        $bangumi=[];
        $all_bangumi = get_option("sinonbangumilist_savedbangumi");
        if ($all_bangumi!=null) {
            $bangumi = $all_bangumi[$id];
        }
        return $bangumi;
    }

    /**
    * Update bangumi status by id
    *
    * @access public
    * @param mixed $id Id of bangumi in bangumi.tv
    * @param mixed $status Status of bangumi, can be 0(for ready), 1(for watching), 2(for finish)
    * @param mixed $times How many times have you finished
    * @param mixed $progress Progress of you watching
    * @return boolean Whether bangumi has been updated or not.
    */
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
            if ($progress>(int)$bangumi['count']) {
                return false;
            }
        }
        
        $all_bangumi[$id]=$bangumi;
        return update_option("sinonbangumilist_savedbangumi", $all_bangumi);
    }

    /**
    * Update bangumi by id
    *
    * @access public
    * @param mixed $id Id of bangumi in bangumi.tv
    * @param mixed $url Url of bangumi in bangumi.tv
    * @param mixed $img Image url of bangumi in bangumi.tv
    * @param mixed $name Original name
    * @param mixed $name_cn Translated name
    * @param mixed $date Air date
    * @param mixed $count Eispode count
    * @param mixed $title Summary or comment
    * @return boolean Whether bangumi has been updated or not.
    */
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
