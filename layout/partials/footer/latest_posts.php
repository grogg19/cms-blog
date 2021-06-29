<?php
/**
 * Latests posts footer partial
 */
?>
<?php if(!empty($latestPosts)) { ?>
<div class="dx-widget-footer">
	<div class="dx-widget-title">
        Свежие публикации
    </div>
    <?php foreach ($latestPosts as $latestPost) { ?>
    <a href="/post/<?= $latestPost->slug ?>" class="dx-widget-post">
        <span class="dx-widget-post-text">
            <span class="dx-widget-post-title"><?= $latestPost->title ?></span>
            <span class="dx-widget-post-date"><?= getDateTime($latestPost->published_at) ?></span>
        </span>
    </a>
    <?php } ?>
</div>
<?php } ?>
