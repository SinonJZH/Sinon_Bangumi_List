<?php
    require_once(ROOT_PATH."/functions/bangumi.php");
    if ($_GET['bangumi_id']==null) {
        return;
    }
    if ($_GET['action']=='delete') {
        bangumi::delete_bangumi_from_id($_GET['bangumi_id']);
        echo("<script>window.location=\"".admin_url()."admin.php?page=sinon_bangumi_list"."\"</script>");
    } else {
        $bangumi = bangumi::get_bangumi_by_id($_GET['bangumi_id']);
        include_once(ROOT_PATH."/views/bangumi-edit-component.php");
    }
