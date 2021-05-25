<?php

use App\Controllers\UserController;
use App\View;

$avatarPath = (new UserController())->getUserAvatarPath();
$token = !empty($token) ? $token : '';
$userRole = !empty($userRole) ? $userRole : 'none';
$comments = !empty($comments) ? $comments : [];
$postId = !empty($postId) ? $postId : 0;
?>
<div class="dx-box mt-55 comments-block" id="comments">
    <?php
    if(count($comments) > 0) {
    ?>
    <h2 class="h4 mb-45">Комментарии:</h2>
		<?php foreach ($comments as $comment) { ?>
            <?php
            /**
             * шаблон комментария
             */
            (new View('partials.comment', [
                'comment' => $comment,
	            'avatarPath' => $avatarPath
            ]))->render();
            ?>
		<?php }?>
    <?php }	else { ?>
	<div class="dx-comment text-center"><i>Нет ни одного комментария.</i></div>
	<?php } ?>
	<?php if($userRole !== 'none') { ?>
    <form method='post' action="/blog/comments/add" class="dx-form mt-50" name="form_add_comment" id="form_add_comment">
        <div class="row vertical-gap">
            <div class="col-12">
	            <input type="hidden" name="_token" value="<?= $token ?>">
	            <input type="hidden" name="postId" value="<?= $postId ?>">
                <textarea class="form-control form-control-style-3" name="commentContent" rows="8" cols="80" placeholder="Ваш комментарий..."></textarea>
            </div>
            <div class="col-12">
                <button type="submit" name="button" class="dx-btn dx-btn-lg" id="save_button" data-form="form_add_comment">Оставить комментарий</button>
            </div>
        </div>
    </form>
	<?php } else { ?>
	<div class="text-center font-weight-normal mt-20">Оставлять комментарии могут только <a href="/signup">зарегистрированные</a> пользователи.</div>
	<?php } ?>
</div>