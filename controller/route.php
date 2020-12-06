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
        add_shortcode('bangumi', array(Static::class , 'create_bangumi_list'));            //显示追番列表页面
        add_action('admin_menu', array(Static::class , 'sinon_bangumi_adminpage'));        //添加后台菜单
        add_action('admin_enqueue_scripts' , array(Static::class , 'admin_page_enqueue')); //加载后台菜单脚本和CSS
        add_action('wp_ajax_Sinon_Bangumi_Ajax_Delete_Single' , array('Sinon_Bangumi_List\data_controller' , 'ajax_delete_single'));//注册ajax删除单个番剧处理函数
        add_action('wp_ajax_Sinon_Bangumi_Ajax_Increase_Progress' , array('Sinon_Bangumi_List\data_controller' , 'ajax_increase_progress'));//注册ajax增加进度函数
    }

     //添加wordpress后台菜单
    public static function sinon_bangumi_adminpage()
    {
        add_management_page('更新追番列表', '更新追番列表', 'edit_posts', 'sinon_bangumi_list', array(Static::class , 'sinon_bangumi_options'));
    }

    //生成追番列表
    public static function create_bangumi_list()
    {
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        $index = data_controller::bangumi_index($saved_bangumi);
        views_controller::bangumi_list($saved_bangumi,$index);
    }

    //加载后台菜单脚本和CSS
    public static function admin_page_enqueue( $hook )
    {
        if($hook != 'tools_page_sinon_bangumi_list')
            return;
        wp_enqueue_script('Sinon_Bangumi_Admin_js', Sinon_BL_PLUGIN_URL . 'js/admin_page.js', array( 'jquery' ));
        $del_single_nonce = wp_create_nonce( 'Sinon_Bangumi_Ajax_Delete_Single' );
        $progress_increase_nonce = wp_create_nonce('Sinon_Bangumi_Ajax_Increase_Progress');
        $script_object = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'del_single_nonce' => $del_single_nonce,
            'progress_increase_nonce' => $progress_increase_nonce
        );
        wp_localize_script( 'Sinon_Bangumi_Admin_js', 'Sinon_object', $script_object );
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
        }else {
            helpers::show_message('抱歉，当前操作无法被验证，请重试！', 'error');
            views_controller::bangumi_option_page();
        }
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
}
?>
