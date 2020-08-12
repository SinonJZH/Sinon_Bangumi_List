<?php require_once(ROOT_PATH."/views/view-helper.php"); ?>
<?php
    //apply settings
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $flag = true;
        if ($_POST["display_mode"]!=null) {
            $flag = $flag & update_option("sinonbangumilist_displaymode", sanitize_text_field($_POST["display_mode"]));
        }

        if ($flag) {
            show_dismissible_notice(__("Settings saved.", "sinon-bangumi-list"), "success");
        }
        else{
            show_dismissible_notice(__("Can't save some settings.", "sinon-bangumi-list"), "error");
        }
    }

    //load settings
    $display_mode = get_option("sinonbangumilist_displaymode");
    if ($display_mode==null) {
        $display_mode="list";
    }
    
?>
<form action="" method="POST">
    <table class="form-table">
        <input name="action" value="do_edit" type="hidden"/>
        <input name="bangumi_id" value="<?php echo($bangumi['id']); ?>" type="hidden"/>
        <tbody>
            <tr>
                <th scope="row"><label for="Display Mode"><?php _e("Image URL", "sinon-bangumi-list"); ?></label></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="radio" name="display_mode" value="comment" <?php echo($display_mode=="comment"?"checked='checked'":""); ?> />
                            <span><?php _e("Comment Mode", "sinon-bangumi-list"); ?></span>
                        </label>
                        <br/>
                        <label>
                            <input type="radio" name="display_mode" value="list"<?php echo($display_mode=="list"?"checked='checked'":""); ?>/>
                            <span><?php _e("List Mode", "sinon-bangumi-list"); ?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>    
                                                                 
        </tbody>
    </table>
    <?php submit_button(); ?>
</form>