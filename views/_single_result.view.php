<tr>
    <td style="width:300px;"><img src="<?php echo ($result['img']); ?>" style="height:200px;"></td>
    <td style="width:800px;">
        <?php _e('番剧名称：') ?><?php echo ($result['name']); ?><br>
        <?php _e('中文名：') ?><?php echo ($result['name_cn']); ?><br>
        <form action="" method="POST">
            <input type="hidden" name="bangumi_id" value="<?php echo ($result['id']); ?>">
            <input type="hidden" name="action" value="1">
            <?php wp_nonce_field('Sinon_Bangumi_Action_Add', 'nonce'); ?>
            <input type="submit" value="<?php _e('添加番剧') ?>" class="button button-primary">
        </form>
    </td>
</tr>
