<?php
/**
 * Список постов в админке
 */

use function Helpers\getDateTime;

$posts = !empty($posts) ? $posts : [];
$token = !empty($token) ? $token : '';
?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-12">
	            <h2 class="m-0"><i class="far fa-list-alt"></i> Список статей блога</h2>
	            <div class="dx-separator mb-10 mt-0"></div>
	            <input type="hidden" name="_token" value="<?= $token ?>">
                <?php
                foreach ($posts as $post) {
                    ?>
                    <div class="dx-blog-item dx-box dx-box-decorated">
                        <div class="dx-blog-item-cont">
                            <h2 class="h3 dx-blog-item-title"><a href="/admin/blog/posts/<?= $post->id ?>/edit"><?= $post->title ?></a></h2>
                            <ul class="dx-blog-item-info">
                                <li class="d-block"><i class="far fa-clock" title="Опубликовано"></i> <?= getDateTime($post->published_at) ?></li>
                                <?php
                                // Условие при котором показывается автор статьи
                                ?>
                                <li class="d-block"><i class="fas fa-user" title="Автор"></i> <a href="#"><?=$post->user->first_name?> <?=$post->user->last_name?></a></li>
                                <?php
                                // --------
                                ?>
                            </ul>
	                        <div class="row">
		                        <div class="col-6">
			                        <a href="/admin/blog/posts/<?= $post->id ?>/edit" class="btn btn-primary"><i class="fas fa-pencil-alt mr-7"></i> Редактировать</a>
		                        </div>
		                        <div class="col-6 text-right">
			                        <a class="btn btn-secondary" href="/admin/blog/posts/delete" role="button" data-type="request" data-value="<?= $post->id ?>" data-action="/admin/blog/posts/delete" data-field="postId"><i class="far fa-trash-alt"></i></a>
		                        </div>
	                        </div>
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