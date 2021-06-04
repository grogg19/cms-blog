<?php
/**
 * View Профиля пользователя
 */

use App\Model\User;
use function Helpers\session;

/**
 * @var string $pathAvatar
 * @var User $user
 * @var string $token
 */
?>

<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-7">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
                        <h2 class="h6 mnt-5 mnb-5"><?= !empty($title) ? $title : '' ?></h2>
	                    <input type="hidden" name="_token" id="_token" value="<?= $token ?>">
                    </div>
                    <div class="dx-separator"></div>
                    <div class="card" style="border: 0;">
                        <?php if (!empty($user->avatar)) { ?>
                            <div class="text-center mt-10">
                            <img src="<?= $pathAvatar . $user->avatar ?>" class="img-thumbnail" alt="..." width="300" >
                            </div>
                        <?php } ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= $user->first_name ?> <?= $user->last_name ?></h5>
                            <b>О себе:</b><p class="card-text"><?= (!empty($user->self_description)) ? $user->self_description : ''; ?></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>email:</b> <?= $user->email ?></li>
                            <li class="list-group-item"><b>Уровень:</b> <?= $user->role->name ?></li>
	                        <li class="list-group-item">
		                        <form id="subscribe_form" name="subscribe_form" action="/manage-subscribes/admin/subscribe">
			                        <input type="hidden" name="_token" value="<?= $token ?>" />
			                        <b class="mr-20">Подписка на рассылку:</b>
			                        <input type="checkbox" id="subscribe_switch" <?=isset($user->subscribeEmail->email) ? 'checked' : ''?>
			                               data-toggle="toggle"
			                               data-style="ios"
			                               data-form="subscribe_form"
			                               data-email="<?= $user->email ?>" />
		                        </form>

	                        </li>
                        </ul>
                    <?php if(!empty(session()->get('userId')) && session()->get('userId') == $user->id) { ?>
	                    <div class="card-body">
		                    <a href="/admin/account/edit" class="card-link">Изменить данные профиля</a>
	                    </div>
                    <?php } ?>
	                    <div id="messageToast"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
