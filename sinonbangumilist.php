<?php
/*
Plugin Name: Sinon的追番列表
Plugin URI: https://sinon.top/sinon-bangumi-list/
Description: 使用短代码[bangumi]在页面上生成追番列表，在“工具-更新追番列表”菜单中配置追番列表。
Version: 2.2.0
Author: SinonJZH
Author URI: https://sinon.top/
*/


//主要功能：在短代码[bangumi]处生成追番列表

define('Sinon_BL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('Sinon_BL_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once Sinon_BL_PLUGIN_DIR . 'controller/route.php';

Sinon_Bangumi_List\route::load();

?>
