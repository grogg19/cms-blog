<?php
/**
 * Список постов в админке
 */

use App\View;
use Illuminate\Pagination\LengthAwarePaginator;
use function Helpers\getDateTime;

/**
 * @var LengthAwarePaginator $posts
 * @var string $token
 * @var $quantity
 */

?>

<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-12">
	            <div class="row align-items-center justify-content-between vertical-gap mnt-20 sm-gap mb-30">
		            <div class="col-auto">
			            <h2 class="h2 mb-0 mt-0"><?= !empty($title) ? $title : ''?></h2>
		            </div>
		            <div class="col pl-10 pr-10 d-none d-sm-block">
			            <div class="dx-separator ml-0 mr-0" id="comments-block"></div>
			            <input type="hidden" name="_token" value="<?= $token ?>">
		            </div>
	            </div>
                <?php
                (new View('partials.quantity_items', [
                    'quantity' => $quantity,
	                'items' => [10, 20, 50, 200, 'all']
                ]))->render();
                ?>
	            <?php if($posts->count() > 0 ) { ?>
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
                <?php
		            if($posts instanceof LengthAwarePaginator) {
                        (new View('partials.pagination', [
                            'paginator' => $posts
                        ]))->render();
		            }
                ?>
	            <?php } else { ?>
	            	<h5 class="mt-30">Список доступных статей пуст</h5>
	            <?php } ?>
	            <div id="messageToast"></div>
            </div>
        </div>
    </div>
</div>