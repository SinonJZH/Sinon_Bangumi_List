<?php
//Insert css
$css_url = esc_url(plugins_url('../css/style.css', __FILE__));
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
        render_bangumi_item($bangumi);
    }
?>

<!-- Watched -->
<h2><?php _e("Watched", "sinon-bangumi-list");echo("($finish_count)"); ?></h2>
<?php
    for ($i = 0; $i < $finish_count; $i++) {
        $id = $index[2][$i];
        $bangumi = $all_bangumi[$id];
        render_bangumi_item($bangumi);
    }
?>

<!-- Ready to Watch-->
<h2><?php _e("Ready to Watch", "sinon-bangumi-list");echo("($ready_count)"); ?></h2>
<?php
    for ($i = 0; $i < $ready_count; $i++) {
        $id = $index[0][$i];
        $bangumi = $all_bangumi[$id];
        render_bangumi_item($bangumi);
    }
?>

<?php
function render_bangumi_item($bangumi)
{
    ?>
    <div class="bangumi-item">
        <div class="bangumi-summary">
            <p>进入21世纪已有近20年，在人们已忘记了妖怪存在的现代。 科学无法解明的现象频发，流言飞语散布各处，大人们只能疲于奔命。 希望想办法解决这一情况而写信给妖怪信箱的13岁少女·真奈的面前， 随着咔哒咔哒的木屐响声，鬼太郎来到了…。</p>
        </div>
        <div class="bangumi-info">
            <img class="bangumi-img" src="https://lain.bgm.tv/pic/cover/l/30/71/234531_u3ujU.jpg">
            <div class="bangumi-detail">
                <strong>鬼太郎 第六季</strong>
                <span>ゲゲゲの鬼太郎</span>
                <br/>
                <span>首播日期：2018年4月1日</span>
                <div class="progress-background">
                    <div class="progress-text">
                        进度
                    </div>
                    <div class="progress-foreground" style="width:50%;">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
}

function render_bangumi_item2($bangumi)
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
