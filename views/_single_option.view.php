<tr>
    <td><?php echo (esc_attr($this_bangumi['name_cn'])); ?></td>
    <td>
        <form action="" method="POST">
            <input type="hidden" name="action" value="2">
            <?php wp_nonce_field('Sinon_Bangumi_Action_Update', 'nonce'); ?>
            <input type="hidden" name="bangumi_id" value="<?php echo (esc_attr($this_bangumi['id'])); ?>">
            <?php if ($this_bangumi['status'] == 0) :  //隐藏待追番状态的周目设定
            ?>
                <input type="hidden" name="times" value="<?php echo ($this_bangumi['times'] ?: 1); ?>">
            <?php else :
                _e('周目：') ?>
                <input type="text" name="times" value="<?php echo ($this_bangumi['times'] ?: 1); ?>" style="width: 40px;text-align: center;">
            <?php endif ?>
            <select name="bg_status">
                <option value="0" <?php if ($this_bangumi['status'] == 0) echo ('selected'); ?>><?php _e('待追番') ?>
                </option>
                <option value="1" <?php if ($this_bangumi['status'] == 1) echo ('selected'); ?>><?php _e('正在追番') ?>
                </option>
                <option value="2" <?php if ($this_bangumi['status'] == 2) echo ('selected'); ?>><?php _e('已追完') ?>
                </option>
            </select>
            <?php if ($this_bangumi['status'] == 1) :   //仅对正在追番状态的番剧显示进度设置
                _e('看番进度：') ?>
                <input type="text" name="progress" value="<?php echo (esc_attr($this_bangumi['progress'])); ?>" style="width: 40px;text-align: center;">
                <?php _e('总集数：'); ?>
                <input type="text" name="count" value="<?php echo (esc_attr($this_bangumi['count'])); ?>" style="width: 40px;text-align: center;">
                <input type="submit" value="<?php _e('修改状态') ?>" class="button button-primary" style="vertical-align:middle;">
        </form>
        <form action="" method="POST"><input type="hidden" name="action" value="2">
            <?php wp_nonce_field('Sinon_Bangumi_Action_Update', 'nonce'); ?>
            <input type="hidden" name="bangumi_id" value="<?php echo (esc_attr($this_bangumi['id'])); ?>">
            <?php if ($this_bangumi['progress'] < $this_bangumi['count'] - 1) :    //判断使用进度+1按钮或已追完按钮
            ?>
                <input type="hidden" name="progress" value="<? echo(esc_attr(intval($this_bangumi['progress'] + 1))); ?>">
                <input type="submit" value="<?php _e('进度+1') ?>" class="button button-primary">
            <?php else : ?>
                <input type="hidden" name="bg_status" value="2">
                <input type="submit" value="<?php _e('设置为已追完') ?>" class="button button-primary">
            <?php endif ?>
        </form>
    <?php else : //对于其他状态补提交按钮和标签
    ?>
        <input type="submit" value="<?php _e('修改状态') ?>" class="button button-primary" style="vertical-align:middle;">
        </form>
    <?php endif ?>
    </td>
    <td>
        <form action="" method="POST">
            <input type="hidden" name="action" value="8">
            <input type="hidden" name="bangumi_id" value="<?php echo (esc_attr($this_bangumi['id'])); ?>">
            <?php wp_nonce_field('Sinon_Bangumi_Action_Edit_Page', 'nonce'); ?>
            <input type="submit" value="<?php _e('编辑') ?>" class="button button-primary" style="vertical-align:middle;">
        </form>
    </td>
    <td>
        <form action="" method="POST">
            <input type="hidden" name="action" value="10">
            <input type="hidden" name="bangumi_id" value="<?php echo (esc_attr($this_bangumi['id'])); ?>">
            <?php wp_nonce_field('Sinon_Bangumi_Action_Delete_Single', 'nonce'); ?>
            <input type="submit" value="<?php _e('删除') ?>" class="button button-primary" style="color:red;vertical-align:middle;">
        </form>
    </td>
</tr>
