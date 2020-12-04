<h2><?php _e('修改番剧信息') ?></h2>
<?php _e('过滤器：') ?>
<form action="" method="GET">
    <select name="filter">
        <option value="" <?php if ($_GET['filter'] == NULL) echo 'selected'; ?>>
            <?php _e('全部') ?>
        </option>
        <option value="0" <?php if ($_GET['filter'] === '0') echo 'selected'; ?>>
            <?php _e('待追番') ?>
        </option>
        <option value="1" <?php if ($_GET['filter'] === '1') echo 'selected'; ?>>
            <?php _e('正在追番') ?>
        </option>
        <option value="2" <?php if ($_GET['filter'] === '2') echo 'selected'; ?>>
            <?php _e('已追完') ?>
        </option>
    </select>
    <input type="hidden" name="page" value="sinon_bangumi_list">
    <input type="submit" class="button button-primary" value="应用">
</form>
<?php if ($saved_bangumi == NULL) :
    _e('看来你还没有添加过番剧呢，要添加一个吗？');
else :
?>
    <table style="line-height: 50px;text-align: center;">
        <tr>
            <td style="width:300px"><?php _e('番剧名称') ?></td>
            <td style="width:500px"><?php _e('番剧状态') ?></td>
            <td><?php _e('编辑') ?></td>
            <td><?php _e('删除') ?></td>
        </tr>
        <?php
        foreach ($saved_bangumi as $this_bangumi) :
            if (key_exists('filter', $_GET) && $_GET['filter'] != "" && $_GET['filter'] != $this_bangumi['status']) continue;   //过滤器控制
            include Sinon_BL_PLUGIN_DIR . 'views/_single_option.view.php';
        endforeach
        ?>
    </table><br>
<?php
endif
?>
<form action="" method="POST"><input type="hidden" name="action" value="1">
    <?php _e('添加番剧id') ?>：<input type="text" name="bangumi_id">
    <?php wp_nonce_field('Sinon_Bangumi_Action_Add', 'nonce'); ?>
    <input type="submit" value="<?php _e('添加番剧') ?>" class="button button-primary">
</form><br>
<form action="" method="POST"><input type="hidden" name="action" value="7">
    <?php _e('按名字搜索番剧：') ?><input type="text" name="keyword">
    <?php wp_nonce_field('Sinon_Bangumi_Action_Search', 'nonce'); ?>
    <input type="submit" value="<?php _e('搜索') ?>" class="button button-primary">
</form><br>
<form action="" method="POST"><input type="hidden" name="action" value="6">
    <?php wp_nonce_field('Sinon_Bangumi_Action_Delete_All', 'nonce'); ?>
    <input type="submit" value="<?php _e('删除全部番剧') ?>" class="button button-primary" style="color:red;">
</form>
