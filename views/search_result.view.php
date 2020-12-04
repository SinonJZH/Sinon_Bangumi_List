<h2><?php _e('搜索结果') ?></h2>
<table style="line-height: 50px;text-align: center;">
    <?php if ($results == null) :
        _e('未搜索到结果！');
    else :
        foreach ($results as $result) :
            include Sinon_BL_PLUGIN_DIR . 'views/_single_result.view.php';
        endforeach;
    endif ?>
</table>
<form action="" method="POST"><input type="hidden" name="wtf">
    <input type="submit" value="<?php _e('放弃添加') ?>" class="button action">
</form>
