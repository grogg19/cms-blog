<?php
/**
 * Форма Login
 */
/**
 * @var $user
 * @var $fieldsForms;
 * @var $title
 */
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/header.php');
?>
<!-- Start Login Form -->
<div class="dx-popup dx-popup-signin" id="block_login" >
    <button type="button" data-fancybox-close class="fancybox-button fancybox-close-small" title="Закрыть"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg></button>
    <div class="dx-signin-content dx-signin text-center">
        <h1 class="h3 text-white mb-30">Вход</h1>
        <form method="post" action="<?= (isset($action)) ? $action : ""?>" class="dx-form" name="form_login" id="form_login" >
            <input type="hidden" name="_token" value="<?=(isset($token)) ? $token : '' ?>">
            <?php if(!empty($fieldsForms)) { ?>
                <?= $fieldsForms ?>
            <?php } ?>
            <div class="dx-form-group-md">
	            <button class="dx-btn dx-btn-block dx-btn-popup" type="submit" name="button" id="save_button" data-form="form_login">Войти</button>
            </div>
            <div class="dx-form-group-md">
                <div class="d-flex justify-content-between">
                    <a href="/signup">Зарегистрироваться</a>
                </div>
            </div>
        </form>
    </div>
	<div id="messageToast"></div>
</div>
<!-- End Login Form -->
<?php
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/footer.php');
?>