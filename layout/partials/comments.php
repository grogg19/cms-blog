<?php

use App\Controllers\UserController;

use function Helpers\getDateTime;

$avatarPath = (new UserController())->getUserAvatarPath();
$token = !empty($token) ? $token : '';
$userRole = !empty($userRole) ? $userRole : 'none';
$comments = !empty($comments) ? $comments : [];
?>
<div class="dx-box mt-55">
    <?php
    if(count($comments) > 0) {
    ?>
    <h2 class="h4 mb-45">Комментарии:</h2>
	<?php foreach ($comments as $comment) { ?>
    <div class="dx-comment">
	    <div>
	        <div class="dx-comment-img"><?= (empty($comment->user->avatar)) ? '<img src="/layout/assets/images/avatar-1.png" alt="">' : '<img src="' . $avatarPath . $comment->user->avatar . '">' ?></div>
            <div class="dx-comment-cont">
                <a href="#" class="dx-comment-name"><?= $comment->user->first_name ?> <?= $comment->user->last_name ?></a>
                <div class="dx-comment-text">
                    <p class="mb-0"><?= htmlspecialchars($comment->content) ?></p>
                </div>
	            <div class="row">
                    <div class="dx-comment-date col-6"><i class="far fa-clock"></i> <?= getDateTime($comment->created_at)?></div>
	                <div class="dx-comment-date col-6 text-right">
		                <?php if(!$comment->has_moderated) { ?>
			            <i class="fas fa-ban" title="Не проверено модератором"></i>
		                <?php } ?>
	                </div>
	            </div>
            </div>
        </div>
    </div>
		<?php }?>
    <?php }	else { ?>
	<div class="dx-comment text-center"><i>Нет ни одного комментария.</i></div>
	<?php } ?>
	<?php if($userRole !== 'none') { ?>
    <form action="/blog/comments/add" class="dx-form mt-50">
        <div class="row vertical-gap">
            <div class="col-md-6">
	            <input type="hidden" name="_token" value="<?= $token ?>">
                <input class="form-control form-control-style-3" type="text" placeholder="Имя">
            </div>
            <div class="col-md-6">
                <input class="form-control form-control-style-3" type="text" placeholder="Вымя">
            </div>
            <div class="col-12">
                <textarea class="form-control form-control-style-3" name="name" rows="8" cols="80" placeholder="Ваш комментарий..."></textarea>
            </div>
            <div class="col-12">
                <button type="button" name="button" class="dx-btn dx-btn-lg">Оставить комментарий</button>
            </div>
        </div>
    </form>
	<?php } else { ?>
	<div>Оставлять комментарии могут только <a href="/signup">зарегистрированные</a> пользователи.</div>
	<?php } ?>
</div>