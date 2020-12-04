<?php

namespace Sinon_Bangumi_List;

class views_controller
{
    //番剧修改菜单
    public static function bangumi_option_page()
    {
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        include Sinon_BL_PLUGIN_DIR.'views/bangumi_option.view.php';
    }

    //生成追番列表页面
    public static function bangumi_list($saved_bangumi,$index)
    {
        wp_enqueue_style('Sinon_Bangumi_Item', Sinon_BL_PLUGIN_URL.'css/style.css');
        $status_0 = count($index[0]);
        $status_1 = count($index[1]);
        $status_2 = count($index[2]);
        include Sinon_BL_PLUGIN_DIR.'views/bangumi_list.view.php';
    }

    //生成番剧搜索结果界面
    public static function search_result_page($results)
    {
        include Sinon_BL_PLUGIN_DIR . 'views/search_result.view.php';
    }

    //生成番剧信息编辑页面
    //@bangumi_info: 要编辑的番剧信息
    //@create: 是否为新增
    public static function bangumi_edit_page($bangumi_info,bool $create = false )
    {
        include Sinon_BL_PLUGIN_DIR . 'views/bangumi_edit.view.php';
    }

    //删除全部番剧确认页面
    public static function del_all_confirm_page()
    {
        include Sinon_BL_PLUGIN_DIR . 'views/del_all_confirm.view.php';
    }

    //删除单个番剧确认页面
    public static function del_single_confirm_page($bangumi)
    {
        include Sinon_BL_PLUGIN_DIR . 'views/del_single_confirm.view.php';
    }
}
