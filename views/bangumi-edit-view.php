<?php
    require_once(ROOT_PATH."/functions/bangumi.php");
    require_once(ROOT_PATH."/views/view-helper.php");
    if ($_GET['bangumi_id']==null) {
        return;
    }
    if ($_GET['action']=='delete') {
        bangumi::delete_bangumi_from_id($_GET['bangumi_id']);
        redirect_to_admin_url("admin.php?page=sinon_bangumi_list", null);
    } else {
        $bangumi = bangumi::get_bangumi_by_id($_GET['bangumi_id']);
        include_once(ROOT_PATH."/views/bangumi-edit-component.php");
    }
