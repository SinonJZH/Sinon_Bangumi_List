<h2><?php _e('正在追番') ?>(<?php echo ($status_1); ?>)</h2>
<?php
for ($i = 0; $i < $status_1; $i++) :
    $id = $index[1][$i];
    include Sinon_BL_PLUGIN_DIR . 'views/_signle_bangumi.view.php';
endfor;

if ($status_1 % 2) :    //排版优化
?>
    <a target="_blank" class="bangumItem" style="box-shadow: none;border:none;"></a>
<?php endif ?>

<h2><?php _e('追完番剧') ?>(<?php echo ($status_2); ?>)</h2>
<?php
for ($i = 0; $i < $status_2; $i++) :
    $id = $index[2][$i];
    include Sinon_BL_PLUGIN_DIR . 'views/_signle_bangumi.view.php';
endfor;
if ($status_1 % 2) :    //排版优化
?>
    <a target="_blank" class="bangumItem" style="box-shadow: none;border:none;"></a>
<?php endif ?>

<h2><?php _e('待追番剧') ?>(<?php echo ($status_0); ?>)</h2>
<?php
for ($i = 0; $i < $status_0; $i++) :
    $id = $index[0][$i];
    include Sinon_BL_PLUGIN_DIR . 'views/_signle_bangumi.view.php';
endfor;
?>
