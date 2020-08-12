
<?php require_once(ROOT_PATH."/functions/bangumi-tv-api.php"); ?>
<?php require_once(ROOT_PATH."/functions/bangumi.php");?>
<?php require_once(ROOT_PATH."/views/view-helper.php");?>
<div class="wrap">
    <h1 class="wp-heading-inline">
    <?php
        if ($bangumi!=null) {
            echo(_e("Edit Bangumi", "sinon-bangumi-list"));
        } else {
            echo(_e("Add Bangumi", "sinon-bangumi-list"));
        }
    ?>
    </h1>
    <?php

        //challenge data status

        if ($bangumi!=null && $_POST['action']!="do_edit") {
            edit_conponent_bangumi_edit_box($bangumi);
        } elseif ($_POST['action']==null) {
            edit_component_bangumi_search_box();
        } elseif ($_POST['action']=="do_edit") {
            $id = (int)$_POST['bangumi_id'];
            $url = sanitize_text_field($_POST['bangumi_url']);
            $img = sanitize_text_field($_POST['image_url']);
            $name = sanitize_text_field($_POST['original_name']);
            $name_cn = sanitize_text_field($_POST['translated_name']);
            $date = sanitize_text_field($_POST['air_date']);
            if (is_numeric($_POST['episode_count'])) {
                $count = (int)sanitize_text_field($_POST['episode_count']);
            } else {
                show_dismissible_notice(__("Invalid episode count", "sinon-bangumi-list"), "error");
                return;
            }
            $title = sanitize_text_field($_POST['summary']);
            $result = bangumi::add_or_update_bangumi($id, $url, $img, $name, $name_cn, $date, $count, $title);
            if ($result==true) {
                show_dismissible_notice(__("Bangumi info saved", "sinon-bangumi-list"), "success");
            } else {
                show_dismissible_notice(__("Failed to save bangumi info", "sinon-bangumi-list"), "error");
            }
        } elseif ($_POST['action']=="search_by_keyword") {
            edit_component_bangumi_search_result($_POST['bangumi_keyword']);
        } elseif ($_POST['action']=='add_by_id') {
            $bangumi = bangumi_tv_api::get_bangumi_info($_POST['bangumi_id']);
            edit_conponent_bangumi_edit_box($bangumi);
        }
    
    ?>

</div>

<?php

function edit_conponent_bangumi_edit_box($bangumi)
{
    ?>
<form action="" method="POST">
    <table class="form-table">
        <input name="action" value="do_edit" type="hidden"/>
        <input name="bangumi_id" value="<?php echo($bangumi['id']); ?>" type="hidden"/>
        <tbody>
            <tr>
                <th scope="row"><label for="image_url"><?php _e("Image URL", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="image_url" type="text" class="regular-text" value="<?php echo($bangumi['img']); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="bangumi_url"><?php _e("Bangumi URL", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="bangumi_url" type="text" class="regular-text" value="<?php echo($bangumi['url']); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="original_name"><?php _e("Original Name", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="original_name" type="text" class="regular-text" value="<?php echo($bangumi['name']); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="translated_name"><?php _e("Translated Name", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="translated_name" type="text" class="regular-text" value="<?php echo($bangumi['name_cn']); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="air_date"><?php _e("Air Date", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="air_date" type="text" class="regular-text" value="<?php echo($bangumi['date']); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="episode_count"><?php _e("Episode Count", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="episode_count" type="number" class="regular-text" value="<?php echo($bangumi['count']); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="summary"><?php _e("Summary", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <textarea name="summary" style="width:50%;height:300px;"><?php echo($bangumi['title']); ?></textarea>
                </td>
            </tr>                                                            
        </tbody>
    </table>
    <?php submit_button(); ?>
</form>
<?php
}

function edit_component_bangumi_search_box()
{
    ?>
<table class="form-table">
    <tbody>
        <tr>
            <form action="" method="POST">
                <th scope="row"><label for="bangumi_id"><?php _e("Bangumi Id", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="action" value="add_by_id" type="hidden"/>
                    <input name="bangumi_id" type="text" class="regular-text"/>
                    <input value="<?php echo(_e("Add Bangumi", "sinon-bangumi-list")); ?>" type="submit" class="button action"/>
                </td>
            </form>
        </tr>
        <tr>
            <form action="" method="POST">
                <th scope="row"><label for="bangumi_keyword"><?php _e("Bangumi Keyword", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <input name="action" value="search_by_keyword" type="hidden"/>
                    <input name="bangumi_keyword" type="text" class="regular-text"/>
                    <input value="<?php echo(_e("Search Bangumi", "sinon-bangumi-list")); ?>" type="submit" class="button action"/>
                </td>
            </form>
        </tr>
    </tbody>
</table>
<?php
}

function edit_component_bangumi_search_result($keyword)
{
    $results = bangumi_tv_api::search_bangumi($keyword);
    $amount = $results['result']; ?>
<div id="the-list">
<?php
    for ($i = 0; $i <= $amount; $i++) {
        ?>

    <div class="plugin-card">
        <div class="plugin-card-top">
            <div class="name column-name" style="margin-left:100px;">
                <h3>
                    <a href="<?php echo($results[$i]['url']); ?>" class="thickbox open-plugin-details-modal">
                    <?php echo($results[$i]['name_cn']); ?>
                    <img src="<?php echo($results[$i]['img']); ?>" class="plugin-icon" alt="" style="width:auto;">
                    </a>
                </h3>
            </div>
            <div class="action-links">
                <ul class="plugin-action-buttons">
                    <li>
                        <form action="" method="POST">
                            <input name="action" value="add_by_id" type="hidden"/>
                            <input name="bangumi_id" value="<?php echo($results[$i]['id']); ?>" type="hidden"/>
                            <input value="<?php echo(_e("Add bangumi", "sinon-bangumi-list")); ?>" type="submit" class="install-now button"/>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="desc column-description" style="margin-left:100px;">
                <p><?php echo($results[$i]['title']); ?></p>
                <p class="authors"><?php echo($results[$i]['name']); ?></p>
            </div>	
        </div>
    </div>
<?php
    } ?>
</div>
<?php
}
