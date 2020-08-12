<?php
function show_dismissible_notice($content, $flag)
{
    ?>
<div id="message" class="updated notice notice-<?php echo($flag); ?> is-dismissible">
    <p><?php echo($content); ?></p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text">Dismiss this notice.</span>
    </button>
</div>
    <?php
}
