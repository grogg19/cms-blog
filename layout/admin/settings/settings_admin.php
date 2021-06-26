<?php
/**
 * Настройки СMS - шаблон
 */

use App\Model\User;
use App\View;
/**
 * @var array $settings
 * @var string $token
 * @var array $settings
 * @var object $parameters
 * @var User $user
 * @var $title
 */
(new View('admin_header', ['user' => $user ?: null, 'title' => $title]))->render();
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-6">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
                        <div class="col pl-10 pr-10 d-none d-sm-block">
                            <div class="container">
	                            <form class="dx-form" name="form_edit_settings" id="form_edit_settings" action="/admin/settings/save">
		                            <div class="row align-items-center justify-content-between vertical-gap mnt-20 sm-gap mb-30">
			                            <div class="col-auto">
				                            <h2 class="h4 mb-0 mt-0"><?= !empty($title) ? $title : ''?></h2>
			                            </div>
			                            <div class="col pl-10 pr-10 d-none d-sm-block">
				                            <div class="dx-separator ml-0 mr-0" id="comments-block"></div>
				                            <input type="hidden" name="_token" value="<?= $token ?>">
			                            </div>
		                            </div>
		                            <div class="dx-form-group-md div-per_page form-element">
			                            <label for="per_page" >Количество постов на главной странице:</label>
			                            <input name="per_page" type="text" id="per_page" class="form-control form-control-style-2" value="<?= $parameters->per_page ?>"/>
		                            </div>
		                            <div class="row justify-content-between mt-30">
			                            <div class="col-auto dx-dropzone-attachment-btn">
				                            <button class="dx-btn dx-btn-lg" type="submit" name="button" id="save_button" data-form="form_edit_settings">Сохранить</button>
			                            </div>
		                            </div>
	                            </form>
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