
<?php require_once(ROOT_PATH."/functions/bangumi-tv-api.php"); ?>
<div class="wrap">
    <h1 class="wp-heading-inline">
    <?php
        if ($bangumi!=null) {
            echo(_e("Edit Bangumi", "sinon-bangumi-list"));
            $_POST['action'] = "add_by_id";
        } else {
            echo(_e("Add Bangumi", "sinon-bangumi-list"));
        }
    ?>
    </h1>
    <?php
        //If in step1, show bangumi search box
        if ($_POST['action']==null) {
            edit_component_bangumi_search_box();
        } elseif ($_POST['action']=="search_by_keyword") {
            //If in step1.5, show bangumi search result
            edit_component_bangumi_search_result($_POST['bangumi_keyword']);
        }
        //If in step2, show bangumi info editing box
    
    ?>

</div>

<?php

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
    $amount = $results['result'];
    ?>
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
                            <input name="bangumi_keyword" value="<?php echo($results[$i]['id']); ?>" type="hidden"/>
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
