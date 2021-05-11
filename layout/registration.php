<?php
/**
 * Шаблон регистрации нового пользователя
 */

use App\FormRenderer;
use function Helpers\generateToken;
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7">
                <div class="dx-box dx-box-decorated">
                    <div class="dx-box-content">
                        <h2 class="h6 mb-6">Регистрация нового пользователя</h2>
                    </div>
                    <div class="dx-separator"></div>
                    <form class="dx-form" name="form_registration" id="form_registration" action="<?= (isset($action)) ? $action : ""?>">
                        <input type="hidden" name="_token" value="<?= generateToken(); ?>">
                        <div class="dx-box-content">
                        <?php
                        if(!empty($fields)) {
                            (new FormRenderer($fields))->render();
                        }
                        ?>
                        </div>
                    </form>
                    <div class="dx-box-content pt-0">
                        <button class="dx-btn dx-btn-lg" type="button" name="button" id="save_button" data-form="form_registration">Зарегистрироваться</button>
                    </div>
	                <div id="messageToast"></div>
                </div>
            </div>
        </div>
    </div>
</div>