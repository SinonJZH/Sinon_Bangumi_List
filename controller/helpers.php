<?php

namespace Sinon_Bangumi_List;

class helpers
{
    //生成提示信息
    //@message: 要显示的信息
    //@type: 信息显示样式  (success/error/info)
    public static function show_message(string $message ="",string $type="success")
    {
        $message = __($message);
        include Sinon_BL_PLUGIN_DIR.'views/_message.view.php';
    }

}
?>
