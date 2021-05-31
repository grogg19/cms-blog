<?php
/**
 * Шаблон вывода списка статей
 */

use App\View;
use function Helpers\getDateTime;

/**
 * @var \Illuminate\Pagination\Paginator $posts
 */

$imgPath = !empty($imgPath) ? $imgPath : '';
?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-8">
                <?php
                foreach ($posts as $post) {
                ?>
                <div class="dx-blog-item dx-box dx-box-decorated">
                    <a href="/post/<?= $post->slug ?>" class="dx-blog-item-img">
                        <?php if(!empty($post->images[0]->file_name)) { ?>
                        <img src="<?=$imgPath . $post->images[0]->file_name?>" alt="">
                        <?php } ?>
                    </a>
                    <div class="dx-blog-item-cont">
                        <h2 class="h3 dx-blog-item-title"><a href="/post/<?= $post->slug ?>"><?= $post->title ?></a></h2>
                        <ul class="dx-blog-item-info">
	                        <li class="d-block"><i class="fas fa-user" title="Автор"></i> <a href="#"><?=$post->user->first_name?> <?=$post->user->last_name?></a></li>
                            <li class="d-block"><i class="far fa-clock" title="Опубликовано"></i> <?= getDateTime($post->published_at) ?></li>
                        </ul>
                        <div class="dx-blog-item-text">
                            <p><?= $post->excerpt ?></p>
                        </div>
                        <a href="/post/<?= $post->slug ?>" class="dx-btn dx-btn-lg">Подробнее</a>
                    </div>
                </div>
                <?php } ?>
                <div class="dx-blog-item pt-0">
                    <a href="#" class="dx-btn dx-btn-lg dx-btn-grey dx-btn-block dx-btn-load" data-btn-loaded="Shown all posts">Показать ещё</a>
                </div>
	            <div id="messageToast"></div>
            </div>
            <div class="col-lg-4">
                <div class="dx-sticky dx-sidebar" data-sticky-offsetTop="120" data-sticky-offsetBot="40">
                    <?php
                    /**
                     * Правый блок сайта
                     */
                    (new View('section_right'))->render();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>