<?php
//Insert css
$display_mode = get_option("sinonbangumilist_displaymode");
if ($display_mode=="comment") {
    $css_url = esc_url(plugins_url('../css/style-comment.css', __FILE__));
} else {
    $css_url = esc_url(plugins_url('../css/style-list.css', __FILE__));
}
wp_enqueue_style('Sinon_Bangumi_Item', $css_url);
//Group bangumi
$all_bangumi = get_option("sinonbangumilist_savedbangumi");
$ready_count = 0;
$watch_count = 0;
$finish_count = 0;
$index = [];
foreach ($all_bangumi as $a) {
    if ($a['status'] == 0) {
        $index[0][$ready_count++] = $a['id'];
    } elseif ($a['status'] == 1) {
        $index[1][$watch_count++] = $a['id'];
    } elseif ($a['status'] == 2) {
        $index[2][$finish_count++] = $a['id'];
    }
}
?>
<!-- In Watching -->
<h2><?php _e("Watching", "sinon-bangumi-list");echo("($watch_count)"); ?></h2>
<?php
    for ($i = 0; $i < $watch_count; $i++) {
        $id = $index[1][$i];
        $bangumi = $all_bangumi[$id];
        if ($display_mode=="comment") {
            render_bangumi_item_comment($bangumi);
        } else {
            render_bangumi_item_list($bangumi);
        }
    }
?>

<!-- Watched -->
<h2><?php _e("Watched", "sinon-bangumi-list");echo("($finish_count)"); ?></h2>
<?php
    for ($i = 0; $i < $finish_count; $i++) {
        $id = $index[2][$i];
        $bangumi = $all_bangumi[$id];
        if ($display_mode=="comment") {
            render_bangumi_item_comment($bangumi);
        } else {
            render_bangumi_item_list($bangumi);
        }
    }
?>

<!-- Ready to Watch-->
<h2><?php _e("Ready to Watch", "sinon-bangumi-list");echo("($ready_count)"); ?></h2>
<?php
    for ($i = 0; $i < $ready_count; $i++) {
        $id = $index[0][$i];
        $bangumi = $all_bangumi[$id];
        if ($display_mode=="comment") {
            render_bangumi_item_comment($bangumi);
        } else {
            render_bangumi_item_list($bangumi);
        }
    }
?>

<?php
function render_bangumi_item_comment($bangumi)
{
    ?>
    <div class="bangumi-item">
        <div class="bangumi-summary">
            <p><?php echo(esc_attr($bangumi['title'])); ?></p>
        </div>
        <div class="bangumi-info">
            <img class="bangumi-img" src="<?php echo(esc_url($bangumi['img'])); ?>">
            <div class="bangumi-detail">
                <a href="<?php echo(esc_url($bangumi['url'])); ?>" class="bangumi-name-cn"><?php echo(esc_attr($bangumi['name_cn'])); ?></a>
                <span class="bangumi-name"><?php echo(esc_attr($bangumi['name'])); ?></span>
                <br/>
                <span><?php _e("Air Date:", "sinon-bangumi-list"); ?><?php echo(esc_attr($bangumi['date'])); ?></span>
                <div class="progress-background">
                    <div class="progress-text">
                    <?php
    $percent = 100;
    if ($bangumi['status']==0) {
        echo(__("Watched:", "sinon-bangumi-list")."0/".esc_attr($bangumi['count']));
        $percent = 0;
    } elseif ($bangumi['status']==2) {
        echo(__("Watched", "sinon-bangumi-list"));
        $percent = 100;
    } else {
        $label_progress = esc_attr($bangumi['times'] != null && $bangumi['times'] > 1 ? ($bangumi['times'].__(" times:", "sinon-bangumi-list")) : __("Watched:", "sinon-bangumi-list"));
        $label_progress = $label_progress.esc_attr($bangumi['progress']).'/'. esc_attr($bangumi['count']);
        echo($label_progress);
        $percent=(float) $bangumi['progress'] / $bangumi['count'] * 100;
    } ?>
                    </div>
                    <div class="progress-foreground" style="width:<?php echo(esc_attr($percent)); ?>%;">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
}

function render_bangumi_item_list($bangumi)
{
    ?>
    
    <a href="<?php echo(esc_url($bangumi['url'])); ?>" target="_blank" class="bangumi-item" title="<?php echo(esc_attr($bangumi['title'])); ?>">
        <img src="<?php echo(esc_url($bangumi['img'])); ?>"><div class="textbox">
        <?php echo(esc_attr($bangumi['name_cn'])); ?>
        <br>
        <?php echo(esc_attr($bangumi['name'])); ?>
        <br>首播日期：<?php echo(esc_attr($bangumi['date'])); ?><br>
        <div class="progress-background">
            <div class="progress-text">
                <?php
    $percent = 100;
    if ($bangumi['status']==0) {
        echo(__("Watched:", "sinon-bangumi-list")."0/".esc_attr($bangumi['count']));
        $percent = 0;
    } elseif ($bangumi['status']==2) {
        echo(__("Watched", "sinon-bangumi-list"));
        $percent = 100;
    } else {
        $label_progress = esc_attr($bangumi['times'] != null && $bangumi['times'] > 1 ? ($bangumi['times'].__(" times:", "sinon-bangumi-list")) : __("Watched:", "sinon-bangumi-list"));
        $label_progress = $label_progress.esc_attr($bangumi['progress']).'/'. esc_attr($bangumi['count']);
        echo($label_progress);
        $percent=(float) $bangumi['progress'] / $bangumi['count'] * 100;
    } ?>
            </div>
            <div class="progress-foreground" style="width:<?php echo(esc_attr($percent)); ?>%;"></div></div>
        </div>
    </a>
    <?php
}
