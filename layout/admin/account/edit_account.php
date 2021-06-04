<?php
/**
 * Редактирование аккаунта пользователя
 */

use App\FormRenderer;
use App\Model\User;
/**
 * @var User $user
 * @var array $form
 */
$user = !empty($user) ? $user : null;
$pathToAvatar = !empty($pathToAvatar) ? $pathToAvatar : '';

?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-7">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
                        <h2 class="h6 mnt-5 mnb-5"><?= !empty($form['title']) ? $form['title'] : '' ?></h2>
                    </div>
                    <div class="dx-separator"></div>
                    <form class="dx-form" name="form_edit_user_profile" id="form_edit_user_profile" action="<?= (isset($form['action'])) ? $form['action'] : ""?>">
                        <input type="hidden" name="_token" value="<?= !empty($token) ? $token : ''?>">
                        <div class="dx-box-content">
                            <?php
                            (new FormRenderer($form['fields']))->render($user);
                            ?>
                            <div class="dx-form-group-md div-avatar form-element mt-20">
                                <label for="avatar">Аватар:</label>

                                <div class="avatar-wrapper">
                                    <?= (!empty($user->avatar)) ? '<img class="profile-pic" src="' . $pathToAvatar . $user->avatar .  '" />' : '<div class="profile-pic"></div>'?>
                                    <div class="upload-button">
                                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                    </div>
                                    <input class="file-upload" type="file" name="avatar" id="avatar" accept="image/*"/>
                                </div>
                            </div>
                            <div class="dx-form-group-md">
                                <button class="dx-btn dx-btn-block dx-btn-popup" type="submit" name="button" id="save_button" data-form="form_edit_user_profile">Сохранить</button>
                            </div>
	                        <div id="messageToast"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>