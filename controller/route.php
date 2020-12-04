<?php

namespace Sinon_Bangumi_List;

require_once Sinon_BL_PLUGIN_DIR . 'controller/helpers.php';
require_once Sinon_BL_PLUGIN_DIR . 'controller/data_controller.php';
require_once Sinon_BL_PLUGIN_DIR . 'controller/views_controller.php';


class route
{
    //插件注册
    public static function load()
    {
        add_shortcode("bangumi", array(Static::class , 'create_bangumi_list'));
        add_action('admin_menu', array(Static::class , 'sinon_bangumi_adminpage'));
    }

     //添加wordpress后台菜单
    public static function sinon_bangumi_adminpage()
    {
        add_management_page('更新追番列表', '更新追番列表', 'edit_posts', 'sinon_bangumi_list', array(Static::class , 'sinon_bangumi_options'));
    }

    //后台菜单框架
    public static function sinon_bangumi_options()
    {
        if (!current_user_can('edit_posts')) {
            wp_die(__('权限不足，无法操作！'));
        }
        if (!key_exists('action', $_POST)) { //默认界面
            views_controller::bangumi_option_page();
        } elseif ($_POST['action'] == 1 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Add")) { //添加番剧确认页面
            self::generate_add_confirm_page();
        } elseif ($_POST['action'] == 2 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Update")) { //更新追番进度
            data_controller::update_bangumi_option();
            views_controller::bangumi_option_page();
        } elseif ($_POST['action'] == 3 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Add_Confirm")) { //添加番剧
            data_controller::add_bangumi_item();
            views_controller::bangumi_option_page();
        } elseif ($_POST['action'] == 4 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Delete_Single_Confirm")) { //删除指定番剧
            data_controller::del_certain_bangunmi();
            views_controller::bangumi_option_page();
        } elseif ($_POST['action'] == 5 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Delete_ALL_Confirm")) { //删除全部番剧
            data_controller::del_all_bangunmi();
            views_controller::bangumi_option_page();
        } elseif ($_POST['action'] == 6 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Delete_All")) { //删除全部番剧确认页面
            views_controller::del_all_confirm_page();
        } elseif ($_POST['action'] == 7 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Search")) { //搜索番剧
            self::search_result();
        } elseif ($_POST['action'] == 8 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Edit_Page")) { //编辑番剧信息页面
            self::edit();
        } elseif ($_POST['action'] == 9 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Edit")) { //编辑番剧信息
            data_controller::edit_process();
            views_controller::bangumi_option_page();
        } elseif ($_POST['action'] == 10 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_Delete_Single")) { //删除单个番剧确认页面
            self::del_single_confirm();
        } else {
            helpers::show_message('抱歉，当前操作无法被验证，请重试！', 'error');
            views_controller::bangumi_option_page();
        }
    }

    //生成追番列表
    public static function create_bangumi_list()
    {
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        $index = data_controller::bangumi_index($saved_bangumi);
        views_controller::bangumi_list($saved_bangumi,$index);
    }

    //生成番剧搜索结果菜单
    public static function search_result()
    {
        $result = data_controller::search_bangumi();
        views_controller::search_result_page($result);
    }

    //番剧信息编辑页面
    public static function edit()
    {
        $id = sanitize_text_field($_POST['bangumi_id']);
        $bangumi_info = data_controller::get_bangumi_info($id);
        if ($bangumi_info == null) {
            helpers::show_message('错误，此番剧id还未添加！', 'error');
            views_controller::bangumi_option_page();
            return null;
        }
        views_controller::bangumi_edit_page($bangumi_info);
    }

    //添加番剧确认页面
    public static function generate_add_confirm_page()
    {
        if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
            helpers::show_message('错误！非法的番剧id！', 'error');
            views_controller::bangumi_option_page();
            return NULL;
        }
        $id = (int)sanitize_text_field( $_POST['bangumi_id']);
        $add = data_controller::get_bangumi_item($id);
        views_controller::bangumi_edit_page($add,true);
    }

    //删除单个番剧确认
    public static function del_single_confirm()
    {
        if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
            helpers::show_message('错误！非法的番剧id！', 'error');
            views_controller::bangumi_option_page();
            return false;
        }
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        $id = (int) sanitize_text_field($_POST['bangumi_id']);
        if(!key_exists($id , $saved_bangumi)){
            helpers::show_message('番剧不存在！', 'error');
            views_controller::bangumi_option_page();
            return false;
        }
        views_controller::del_single_confirm_page($saved_bangumi[$id]);
        }
}
?>
