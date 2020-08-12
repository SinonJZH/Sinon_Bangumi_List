<?php
function show_dismissible_notice($content, $flag)
{
    ?>
<div id="message" class="notice notice-<?php echo($flag); ?> is-dismissible">
    <p><?php echo($content); ?></p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text">Dismiss this notice.</span>
    </button>
</div>
    <?php
}

function redirect_to_admin_url($url, $delay)
{
    if ($url!=null) {
        if ($delay!=null) {
            echo("<script>window.setTimeout(function(){window.location='".admin_url()."$url';},$delay)</script>");
        } else {
            echo("<script>window.location='".admin_url()."$url'</script>");
        }
    } else {
        if ($delay!=null) {
            echo("<script>window.setTimeout(function(){window.location.reload(true);},$delay)</script>");
        } else {
            echo("<script>window.location.reload(true);</script>");
        }
    }
}
