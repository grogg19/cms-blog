<?php
/**
 * Шаблон вывода одной статьи
 */
use App\View;
use function Helpers\getDateTime;

$post = !empty($post) ? $post : null;
$imgPath = !empty($imgPath) ? $imgPath : '';
$comments = !empty($comments) ? $comments : null;
$userRole = !empty($userRole) ? $userRole : 'none';
?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-8">
                <div class="dx-blog-post dx-box dx-box-decorated">
                    <?php
                    if(!empty($post->images[0]->file_name)) {
                        ?>
                        <div class="dx-gallery pt-10 pr-10 pl-10">
                            <a href="<?=$imgPath . $post->images[0]->file_name?>" data-fancybox="images" class="dx-gallery-item">
                                <span class="dx-gallery-item-overlay"><span class="icon pe-7s-exapnd2"></span></span>
                                <img src="<?=$imgPath . $post->images[0]->file_name?>" class="dx-img" alt="">
                            </a>
                        </div>
                    <?php } ?>
                    <div class="dx-blog-post-box">
                        <h1 class="h3 dx-blog-post-title"><?= $post->title ?></h1>
                        <ul class="dx-blog-post-info">
                            <li>Дата публикации: <?= getDateTime($post->published_at) ?></li>
                            <li>Автор: <a href="#"><?= $post->user->first_name?> <?=$post->user->last_name ?></a></li>
                        </ul>
                    </div>
                    <div class="dx-blog-post-box">
                        <?= $post->content ?>
                    </div>
                    <?php
                    if(!empty($post->images)) {
                        ?>
                        <div class="dx-blog-post-box">
                            <div class="dx-gallery">
                                <div class="row vertical-gap">
                                    <?php foreach ($post->images as $key => $image) {
                                        if ( $key === 0 ) continue;
                                    ?>
                                    <div class="col-6">
                                        <a href="<?=$imgPath . $image->file_name?>" data-fancybox="images" class="dx-gallery-item">
                                            <span class="dx-gallery-item-overlay dx-gallery-item-overlay-md"><span class="icon pe-7s-exapnd2"></span></span>
                                            <img src="<?=$imgPath . $image->file_name?>" class="dx-img" alt="">
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="dx-separator"></div>
                    <div class="dx-blog-post-box pt-30 pb-30">
                        <ul class="dx-blog-post-tags mnt-6 mnb-1 db">
                            <li class="dx-blog-post-tags-title">Tags:</li>
                            <li><a href="#">Branding</a></li>
                            <li><a href="#">Design</a></li>
                            <li><a href="#">People</a></li>
                        </ul>
                    </div>
                </div>
	            <?php
	            	/** Блок комментариев к посту*/
                    (new View('partials.comments', [
                    		'comments' => $comments,
	                        'user' => $post->user,
	                        'userRole' => $userRole
                    ]))->render();
	            ?>
            </div>
            <div class="col-lg-4">
                <div class="dx-sticky" data-sticky-offsetTop="120" data-sticky-offsetBot="40">
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
