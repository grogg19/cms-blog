<?php
/**
 * Список постов в админке
 */

use function Helpers\getDateTime;

$posts = !empty($posts) ? $posts : [];
?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-12">
                <?php
                foreach ($posts as $post) {
                    ?>
                    <div class="dx-blog-item dx-box dx-box-decorated">
                        <div class="dx-blog-item-cont">
                            <h2 class="h3 dx-blog-item-title"><a href="/admin/blog/posts/<?= $post->id ?>/edit"><?= $post->title ?></a></h2>
                            <ul class="dx-blog-item-info">
                                <li>Опубликовано: <?= getDateTime($post->published_at) ?></li>
                                <?php
                                // Условие при котором показывается автор статьи
                                ?>
                                <li>Автор: <a href="#"><?=$post->user->first_name?> <?=$post->user->last_name?></a></li>
                                <?php
                                // --------
                                ?>
                            </ul>
                            <a href="/admin/blog/posts/<?= $post->id ?>/edit" class="dx-btn dx-btn-lg">Редактировать</a>
                        </div>
                    </div>
                <?php } ?>
                <div class="dx-blog-item pt-0">
                    <a href="#" class="dx-btn dx-btn-lg dx-btn-grey dx-btn-block dx-btn-load" data-btn-loaded="Shown all posts">Load More Post</a>
                </div>
	            <div id="messageToast"></div>
            </div>
        </div>
    </div>
</div>