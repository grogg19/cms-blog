<?php
/**
 * Форма Регистрации нового пользователя
 */
use App\View;
use App\FormRenderer;

/**
 * @var $fields
 * @var $title
 */

(new View('header',['title' => $title]))->render();
?>
<!-- Start SignUp Form -->
<div class="dx-popup dx-popup-signin" id="block_signup" >
    <button type="button" data-fancybox-close class="fancybox-button fancybox-close-small" title="Close"><svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg></button>
    <div class="dx-signin-content dx-signin text-center">
        <h1 class="h3 text-white mb-30"><?= !empty($title) ? $title : '' ?></h1>
        <form action="<?= !empty($action) ? $action : ""?>" class="dx-form" id="form_signup" name="form_signup" method="post">
            <input type="hidden" name="_token" value="<?=!empty($token) ? $token : '' ?>">
            <?php
            (new FormRenderer($fields))->render();
            ?>
	        <div class="custom-control custom-switch mt-20 mb-20">
		        <input type="checkbox" class="custom-control-input" name="agreement" id="agreement">
		        <label class="custom-control-label text-white text-left pl-10 pt-2" for="agreement">
			        Согласен с <a href="/pravila-polzovaniya-sajtom">правилами сайта</a>
		        </label>
	        </div>
            <div class="dx-form-group-md">
                <button class="dx-btn dx-btn-block dx-btn-popup" type="submit" name="button" id="save_button" data-form="form_signup">Зарегистрироваться</button>
            </div>
            <div class="dx-form-group-md">
                <div class="d-flex justify-content-between">
                    <a href="/login" >Войти</a>
                </div>
            </div>
        </form>
    </div>
	<div id="messageToast"></div>
</div>
<!-- End Login Form -->
<?php
(new View('footer'))->render();
?>