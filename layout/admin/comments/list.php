<?php
/**
 * Список комментариев - шаблон
 */

use App\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Model\User;
use function Helpers\getDateTime;
/**
 * @var Collection $comments
 * @var string $avatarPath
 * @var string $token
 * @var $quantity
 * @var User $user
 * @var $title
 */

(new View('admin_header', ['user' => $user ?: null, 'title' => $title]))->render();
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-xl-12">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
	                    <div class="col pl-10 pr-10 d-none d-sm-block">
		                    <div class="container">
			                    <div class="row align-items-center justify-content-between vertical-gap mnt-20 sm-gap mb-30">
				                    <div class="col-auto">
					                    <h2 class="h4 mb-0 mt-0"><?= !empty($title) ? $title : ''?></h2>
				                    </div>
				                    <div class="col pl-10 pr-10 d-none d-sm-block">
					                    <div class="dx-separator ml-0 mr-0" id="comments-block"></div>
					                    <input type="hidden" name="_token" value="<?= $token ?>">
				                    </div>
			                    </div>
                                <?php
                                // Форма "Количество элементов на странице"
                                (new View('partials.quantity_items', [
                                    'quantity' => $quantity,
                                    'items' => [10, 20, 50, 200, 'all']
                                ]))->render();
                                ?>
                                <?php if($comments->count() > 0 ) { ?>
			                        <?php foreach ($comments as $comment) { ?>
				                    <div class="dx-ticket-item dx-comment
					                     <?= ($comment->has_moderated) ? 'dx-ticket-open' : 'dx-ticket-closed' ?>
					                     dx-block-decorated" data-comment-id="<?= $comment->id ?>">
									    <span class="dx-ticket-img">
									        <img src="<?= $avatarPath . DIRECTORY_SEPARATOR .$comment->user->avatar ?>"
									             alt="<?= $comment->user->first_name ?> <?= $comment->user->last_name ?>">
									    </span>
					                    <span class="dx-ticket-cont">
									        <span class="dx-ticket-name">
									            <?= $comment->user->role->name ?>: <?= $comment->user->first_name ?> <?= $comment->user->last_name ?>
									        </span>
									        <span class="dx-ticket-title h5">
									            <?=$comment->post->title?>
									        </span>
									        <ul class="dx-ticket-info">
									            <li>Дата комментирования: <?= getDateTime($comment->created_at)?></li>
									            <li>
										            <a href="#" class="btn-comment-content" data-id="<?= $comment->id ?>">Посмотреть</a>
									            </li>
									            <li>
										            <?php if($comment->has_moderated) { ?>
										            <a href="/admin/posts/comments/reject" data-type="request" data-value="<?= $comment->id ?>" data-field="commentId">Отклонить</a>
										            <?php } else { ?>
											        <a href="/admin/posts/comments/approve" data-type="request" data-value="<?= $comment->id ?>" data-field="commentId">Одобрить</a>
										            <?php } ?>
									            </li>
									        </ul>
						                    <div class="mt-30 mb-30 comment-content comment-hidden"  data-content-id="<?= $comment->id ?>"><?= $comment->content ?></div>
									    </span>
					                    <span class="dx-ticket-status">
									        <?= ($comment->has_moderated) ? 'Проверено' : 'Не проверено' ?>
									    </span>
				                    </div>
			                        <?php } ?>
                                    <?php
	                                //  Пагинация
                                    if($comments instanceof LengthAwarePaginator) {
                                        (new View('partials.pagination', [
                                            'paginator' => $comments
                                        ]))->render();
                                    }
                                    ?>
                                <?php } else { ?>
	                                <h5 class="mt-30">Список доступных комментариев пуст</h5>
                                <?php } ?>
		                    </div>
		                    <div id="messageToast"></div>
	                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
(new View('footer'))->render();
?>