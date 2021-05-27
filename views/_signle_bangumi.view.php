<?php $is_mul = array_key_exists('times', $saved_bangumi[$id]) && $saved_bangumi[$id]['times'] > 1?>
<a href="<?php echo (esc_url($saved_bangumi[$id]['url'])); ?>" target="_blank" class="bangumItem" title="<?php echo (esc_attr($saved_bangumi[$id]['title'])); ?>">
    <img src="<?php echo (esc_url($saved_bangumi[$id]['img'])); ?>">
    <div class="textBox"><?php echo (esc_attr($saved_bangumi[$id]['name_cn'])); ?>
        <br>
        <?php echo (esc_attr($saved_bangumi[$id]['name'])); ?>
        <br>
        <?php _e('首播日期：') ?><?php echo (esc_attr($saved_bangumi[$id]['date'])); ?>
        <br>
        <div class="jinduBG<?php if($is_mul){?>_m<?php }?>">
            <div class="jinduText">
                <?php if ($is_mul) :  //仅在周目大于1时显示周目
                    echo esc_attr($saved_bangumi[$id]['times']);
                ?>
                    <?php _e('周目：') ?>
                <?php endif ?>
                <?php if ($saved_bangumi[$id]['status'] == 1) : //正在追番状态进度显示
                ?>
                    <?php _e('进度：') ?>
                    <?php echo (esc_attr($saved_bangumi[$id]['progress'])); ?>/<?php echo (esc_attr($saved_bangumi[$id]['count'])); ?>
            </div>
            <div class="jinduFG<?php if($is_mul){?>_m<?php }?>" style="width:<?php echo (esc_attr((string) (round((float) $saved_bangumi[$id]['progress'] / $saved_bangumi[$id]['count'] * 100, 2)))); ?>%;">
            </div>
        <?php elseif ($saved_bangumi[$id]['status'] == 0) :   //待追番状态进度显示
        ?>
            <?php _e('进度：') ?>0/<?php echo (esc_attr($saved_bangumi[$id]['count'])); ?>
        </div>
        <div class="jinduFG" style="width:0%;">
        </div>
        <?php elseif ($saved_bangumi[$id]['status'] == 2) :   //已追完状态进度显示
        ?>
            <?php _e('已追完') ?>
        </div>
        <div class="jinduFG<?php if($is_mul){?>_m<?php }?>" style="width:100%;">
        </div>
    <?php endif ?>
    </div>
    </div>
</a>
