<p style="font-size:30px;color:red"><?php _e('警告！你即将删除所有番剧！该操作不可逆！') ?></p>
<form action="" method="POST">
    <input type="hidden" name="action" value="5">
    <?php wp_nonce_field('Sinon_Bangumi_Action_Delete_ALL_Confirm', 'nonce'); ?>
    <input type="submit" value="<?php _e('确认删除') ?>" class="button button-primary" style="color:red;">
</form>
<form action="" method="POST">
    <input type="submit" value="<?php _e('放弃删除') ?>" class="button action">
</form>
