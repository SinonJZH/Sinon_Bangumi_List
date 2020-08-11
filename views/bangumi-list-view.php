<?php
    //Update Status
    if ($_POST['action']=="update_status") {
        //Load bangumi
        $all_bangumi = get_option("sinonbangumilist_savedbangumi");
        $bangumi = $all_bangumi[$_POST['id']];
        //Update
        if ((int)$_POST['bg_status']!=1) {
        }
        $bangumi['status'] = (int)sanitize_text_field($_POST['bg_status']);
        $all_bangumi[$_POST['id']] = $bangumi;
        echo(var_dump($all_bangumi));
        update_option("sinonbangumilist_savedbangumi", $all_bangumi);
    }
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e("Bangumi List", "sinon-bangumi-list") ?></h1>
    <a class="page-title-action" href="<?php echo admin_url("admin.php?page=sinon_bangumi_new"); ?>"><?php _e("Add new bangumi", "sinon-bangumi-list") ;?></a>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>              
                <th scope="col" class="manage-column column-primary"><?php _e("Bangumi Name", "sinon-bangumi-list"); ?></th>
                <th scope="col"><?php _e("Status", "sinon-bangumi-list"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $all_bangumi = get_option("sinonbangumilist_savedbangumi");
                if (($all_bangumi == null) || (count($all_bangumi)==0)) {
                    echo("<tr><th span=\"4\">".__("No Bangumi", "obsidian-auth")."</th></tr>");
                } else {
                    foreach ($all_bangumi as $bangumi) {
                        ?>
                        <tr>
                            <th>
                                <strong><a class="row-title"><?php echo($bangumi["name_cn"]) ?></a></strong>
                                <div class="row-actions">
                                        <span class="edit">
                                            <a href="<?php echo(admin_url()."admin.php?page=sinon_bangumi_new&bangumi_id=".$bangumi["id"]); ?>"><?php _e("Edit", "sinon-bangumi-list"); ?></a> | 
                                        </span>
                                        <span class="delete">
                                            <a href="<?php echo(admin_url()."admin.php?page=sinon_bangumi_new&bangumi_id=".$bangumi["id"]."&action=delete"); ?>"><?php _e("Delete", "sinon-bangumi-list"); ?></a>
                                        </span>
                                </div>
                            </th>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" value="update_status" name="action"/>
                                    <input type="hidden" value="<?php echo($bangumi["id"]) ?>" name="id"/>
                                    <select name="bg_status">
                                        <option value=0 <?php echo($bangumi['status']==0?"selected":""); ?>><?php echo(_e("Ready to Watch", "sinon-bangumi-list")); ?></option>
                                        <option value=1 <?php echo($bangumi['status']==1?"selected":""); ?>><?php echo(_e("In Watching", "sinon-bangumi-list")); ?></option>
                                        <option value=2 <?php echo($bangumi['status']==2?"selected":""); ?>><?php echo(_e("Watched", "sinon-bangumi-list")); ?></option>
                                    </select>
                                    <input type="submit" value="<?php echo(_e("Update Status", "sinon-bangumi-list")); ?>" class="button button-primary">
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
            ?>
        </tbody>
    </table>
</div>