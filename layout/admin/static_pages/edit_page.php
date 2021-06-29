<?php
/**
 * Шаблон редактирования статической страницы
 */

/**
 * @var $title
 * @var $user
 */
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/admin_header.php');
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="dx-box dx-box-decorated">
                    <div class="dx-box-content">
	                    <div class="row align-items-center justify-content-between vertical-gap mnt-30 sm-gap mb-10">
	                    <div class="col-auto">
		                    <h2 class="h2 mb-0 mt-0"><?= $title ?></h2>
	                    </div>
	                    <div class="col pl-0 pr-0 d-none d-sm-block">
		                    <div class="dx-separator ml-10 mr-10"></div>
	                    </div>
                    </div>
                        <!-- START: Breadcrumbs -->
                        <ul class="dx-breadcrumbs text-left dx-breadcrumbs-dark mnb-6 fs-14">
                            <li><a href="/admin/static-pages">Статические страницы</a></li>
                            <li>Редактирование страницы</li>
                        </ul>
                        <!-- END: Breadcrumbs -->
                    </div>
                    <div class="dx-separator"></div>
                    <form class="dx-form" name="form_edit_page" id="form_edit_page" action="<?= (isset($form['action'])) ? $form['action'] : ""?>">
                        <input type="hidden" name="_token" value="<?=(!empty($token) ? $token : '')?>">
	                    <input type="hidden" name="edit_form" value="1">
                        <div class="dx-box-content">
                            <?php if(!empty($formFields)) {?>
                                <?= htmlspecialchars_decode($formFields) ?>
                            <?php } ?>
                        </div>
                    </form>
	                <div class="dx-box-content">
		                <button class="dx-btn dx-btn-lg" type="submit" name="button" id="save_button" data-form="form_edit_page">Сохранить</button>
	                </div>
	                <div id="messageToast"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/footer.php');
?>
