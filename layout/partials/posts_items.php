<?php

use Illuminate\Database\Eloquent\Collection;
use function Helpers\getDateTime;
/**
 * @var Collection $posts
 * @var string $imgPath
 */

if($posts->count() > 0) {
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
                <li class="d-block"><i class="fas fa-user" title="Автор"></i> <a href="#"><?= $post->user->first_name ?> <?=$post->user->last_name?></a></li>
                <li class="d-block"><i class="far fa-clock" title="Опубликовано"></i> <?= getDateTime($post->published_at) ?></li>
            </ul>
            <div class="dx-blog-item-text">
                <p><?= $post->excerpt ?></p>
            </div>
            <a href="/post/<?= $post->slug ?>" class="dx-btn dx-btn-lg">Подробнее</a>
        </div>
    </div>
<?php }
}
?>
