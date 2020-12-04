<h2><?php _e('删除番剧确认') ?></h2>
<?php _e('你即将要删除的番剧为：') ?><?php echo (esc_attr($bangumi['name_cn'])); ?><?php _e('，确认删除吗？') ?><br>
<form action="" method="POST"><input type="hidden" name="action" value="4">
    <input type="hidden" name="bangumi_id" value="<?php echo (esc_attr($bangumi['id'])); ?>">
    <?php wp_nonce_field('Sinon_Bangumi_Action_Delete_Single_Confirm', 'nonce'); ?>
    <input type="submit" value="<?php _e('确认删除') ?>" class="button button-primary" style="color:red;">
</form>
<form action="" method="POST">
    <input type="hidden" name="wtf">
    <input type="submit" value="<?php _e('放弃删除') ?>" class="button action">
</form>
