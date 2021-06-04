<tr id="<?php echo (esc_attr($this_bangumi['id'])); ?>">
    <td>
        <a href="<?php echo (esc_url($this_bangumi['url'])); ?>" id="<?php echo (esc_attr($this_bangumi['id'])); ?>-name" target="_blank">
            <?php echo (esc_attr($this_bangumi['name_cn'])); ?>
        </a>
    </td>
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
            <select name="bg_status" id="<?php echo (esc_attr($this_bangumi['id'])); ?>-status">
                <option value="0" <?php if ($this_bangumi['status'] == 0) echo ('selected'); ?>><?php _e('待追番') ?>
                </option>
                <option value="1" <?php if ($this_bangumi['status'] == 1) echo ('selected'); ?>><?php _e('正在追番') ?>
                </option>
                <option value="2" <?php if ($this_bangumi['status'] == 2) echo ('selected'); ?>><?php _e('已追完') ?>
                </option>
            </select>
            <?php if ($this_bangumi['status'] == 1) :   //仅对正在追番状态的番剧显示进度设置
            ?>
                <?php _e('看番进度：') ?>
                <input type="text" name="progress" value="<?php echo (esc_attr($this_bangumi['progress'])); ?>" style="width: 40px;text-align: center;" id="<?php echo (esc_attr($this_bangumi['id'])); ?>-progress">
                <?php _e('总集数：'); ?>
                <input type="text" name="count" value="<?php echo (esc_attr($this_bangumi['count'])); ?>" style="width: 40px;text-align: center;" id="<?php echo (esc_attr($this_bangumi['id'])); ?>-count">
                <input type="submit" value="<?php _e('修改状态') ?>" class="button button-primary" style="vertical-align:middle;">
        </form>
        <button type="button" class="button button-primary" onclick="bangumi_increase_progress(<?php echo (esc_attr($this_bangumi['id'])); ?>);" id="<?php echo (esc_attr($this_bangumi['id'])); ?>-increase_button">
            <?php if ($this_bangumi['progress'] < $this_bangumi['count'] - 1) :    //判断显示进度+1按钮或已追完
                    _e('进度+1');
                else :
                    _e('设置为已追完');
                endif; ?>
        </button>
        <span id="<?php echo (esc_attr($this_bangumi['id'])); ?>-increase_message" style="display:none;"></span>
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
        <button type="button" class="button button-primary" style="vertical-align:middle;color:red;" onclick="bangumi_del_confirm(<?php echo (esc_attr($this_bangumi['id'])); ?>);"><?php _e('删除') ?></button>
    </td>
</tr>
