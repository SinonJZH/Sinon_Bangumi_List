<?php
    require_once(ROOT_PATH."/functions/bangumi.php");
    if ($_GET['bangumi_id']==null) {
        return;
    }
    $bangumi = bangumi::get_bangumi_by_id($_GET['bangumi_id']);
    include_once(ROOT_PATH."/views/bangumi-edit-component.php");