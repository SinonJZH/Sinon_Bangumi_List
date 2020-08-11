<?php
class bangumi_tv_api
{
    /**
    * Get bangumi info from bangumi.tv
    *
    * @access public
    * @param mixed $id Id of bangumi in bangumi.tv
    * @return array Bangumi item
    */
    public static function get_bangumi_info($id)
    {
        $URL = "http://api.bgm.tv/subject/" . (string) $id . "?responseGroup=Small";
        $request = wp_remote_get($URL);
        $response = wp_remote_retrieve_body($request);
        ;
        $bg_json = json_decode($response, true);
        $date = explode('-', $bg_json['air_date']);
        $date[1] = ltrim($date[1], '0');
        $date[2] = ltrim($date[2], '0');
        //返回需要的数据
        $bangumi_item['url'] = $bg_json['url'];
        $bangumi_item['name_cn'] = $bg_json['name_cn'];
        $bangumi_item['name'] = $bg_json['name'];
        $bangumi_item['img'] = $bg_json['images']['large'];
        $bangumi_item['date'] = $date[0] . '年' . $date[1] . '月' . $date[2] . '日';
        $bangumi_item['count'] = $bg_json['eps_count'];
        $bangumi_item['title'] = $bg_json['summary'];
        $bangumi_item['id'] = $bg_json['id'];
        if ($bangumi_item['name_cn'] == "") {
            $bangumi_item['name_cn'] = $bg_json['name'];
            $bangumi_item['name'] = "";
        }
        return $bangumi_item;
    }

    /**
    * Search bangumi from bgm.tv
    *
    * @access public
    * @param mixed $keyword
    * @return array Available results
    */
    public static function search_bangumi($keyword)
    {
        $URL = "http://api.bgm.tv/search/subject/:" . urlencode($keyword) . "?type=2&responseGroup=Large&max_results=10";
        $request = wp_remote_get($URL);
        $response = wp_remote_retrieve_body($request);
        ;
        $bg_json = json_decode($response, true);
        for ($i = 0; $i < 10; $i++) {
            if ($bg_json['list'][$i]['id'] == null) {
                break;
            }
            $result['result'] = $i;
            $result[$i]['id'] = $bg_json['list'][$i]['id'];
            $result[$i]['url'] = $bg_json['list'][$i]['url'];
            $result[$i]['name'] = $bg_json['list'][$i]['name'];
            $result[$i]['name_cn'] = $bg_json['list'][$i]['name_cn'];
            $result[$i]['img'] = $bg_json['list'][$i]['images']['large'];
        }
        return $result;
    }
}
