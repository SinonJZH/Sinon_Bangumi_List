<h2><?php _e('编辑番剧信息') ?></h2>
<form action="" method="POST">
    <input type="hidden" name="bangumi_id" value="<?php echo ($bangumi_info['id']); ?>">
    <?php if ($create) :      //若为添加模式
    ?>
        <input type="hidden" name="action" value="3">
        <?php wp_nonce_field('Sinon_Bangumi_Action_Add_Confirm', 'nonce'); ?>
    <?php else : ?>
        <input type="hidden" name="action" value="9">
        <?php wp_nonce_field('Sinon_Bangumi_Action_Edit', 'nonce'); ?>
    <?php endif ?>
    <img src="<?php echo ($bangumi_info['img']); ?>" style="max-height:600px;max-width:30%;float:right;margin-right:10%;"></img><br>
    <?php _e('图片链接：') ?><input type="text" name="img" value="<?php echo ($bangumi_info['img']) ?>" style="width:50%"><br>
    <?php _e('番剧链接：') ?><input type="text" name="url" value="<?php echo ($bangumi_info['url']) ?>" style="width:50%"><br>
    <?php _e('中文名：') ?><input type="text" name="name_cn" value="<?php echo ($bangumi_info['name_cn']) ?>" style="width:50%"><br>
    <?php _e('日文名：') ?><input type="text" name="name" value="<?php echo ($bangumi_info['name']) ?>" style="width:50%"><br>
    <?php _e('放送日期：') ?><input type="text" name="date" value="<?php echo ($bangumi_info['date']) ?>" style="width:50%"><br>
    <?php _e('总话数：') ?><input type="text" name="count" value="<?php echo ($bangumi_info['count']) ?>" style="width:50%"><br>
    <?php _e('简介：') ?><textarea style="width:50%;height:300px;" name="title"><?php echo ($bangumi_info['title']) ?></textarea><br>
    <input type="submit" value="<?php $create ? _e('确认添加') : _e('确认修改'); ?>" class="button button-primary">
</form>
<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="<?php _e('放弃修改') ?>" class="button action">
</form>
