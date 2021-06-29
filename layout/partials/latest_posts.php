<?php
/**
 * Latests posts partial
 */

/**
 * @var $latestPosts
 * @var $imgPath
 * @var $postRepository
 */
?>
<?php if(!empty($latestPosts)) { ?>
<div class="dx-widget dx-box dx-box-decorated mnt-0">
    <div class="dx-widget-title">Свежие публикации</div>
    <?php foreach ($latestPosts as $latestPost) { ?>
    <a href="/post/<?= $latestPost->slug ?>" class="dx-widget-post">
        <?php
        if(!empty($latestPost->images[0]->file_name)) {
            ?>
            <span class="dx-widget-post-img"><img src="<?= $imgPath . $latestPost->images[0]->file_name ?>" alt=""></span>
        <?php } ?>
        <span class="dx-widget-post-text">
            <span class="dx-widget-post-title"><?= $latestPost->title ?></span>
            <span class="dx-widget-post-date"><i class="far fa-clock" title="Опубликовано"></i> <?= getDateTime($latestPost->published_at) ?></span>
        </span>
    </a>
    <?php } ?>
</div>
<?php } ?>