<?php
use function Helpers\getDateTime;
use App\Model\Comment;
/**
 * @var string $avatarPath;
 * @var Comment $comment;
 */
?>
<div class="dx-comment">
    <div>
        <div class="dx-comment-img"><?= (empty($comment->user->avatar)) ? '<img src="/layout/assets/images/avatar-1.png" alt="">' : '<img src="' . $avatarPath . $comment->user->avatar . '">' ?></div>
        <div class="dx-comment-cont">
            <a href="#" class="dx-comment-name"><?= $comment->user->first_name ?> <?= $comment->user->last_name ?></a>
            <?php if(!$comment->has_moderated) { ?>
                <div class="small op-5">Не проверено модератором</div>
            <?php } ?>
            <div class="dx-comment-text">
                <p class="mb-0"><?= htmlspecialchars($comment->content) ?></p>
            </div>
            <div class="dx-comment-date"><i class="far fa-clock"></i> <?= getDateTime($comment->created_at)?></div>
        </div>
    </div>
</div>