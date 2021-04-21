<?php
/**
 * Шаблон
 * Создание поста
 */
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7">
                <div class="dx-box dx-box-decorated">
                    <div class="dx-box-content">
                        <h2 class="h6 mb-6">Создание новой страницы</h2>


                        <!-- START: Breadcrumbs -->
                        <ul class="dx-breadcrumbs text-left dx-breadcrumbs-dark mnb-6 fs-14">

                            <li><a href="/admin">Раздел администрирования</a></li>

                            <li>Создание новой страницы</li>

                        </ul>
                        <!-- END: Breadcrumbs -->

                    </div>
                    <div class="dx-separator"></div>
                    <form class="dx-form" name="form_create_page" id="form_create_page" action="<?= (isset($form['action'])) ? $form['action'] : ""?>">
                        <input type="hidden" name="_token" value="<?=(!empty($token) ? $token : '')?>">
                        <div class="dx-box-content">
                            <?php
                            (new \App\FormRenderer($form['fields']))->render();
                            ?>
                        </div>
                    </form>
	                <div class="dx-box-content">
		                <button class="dx-btn dx-btn-lg" type="submit" name="button" id="save_button" data-form="form_create_page">Сохранить</button>
	                </div>

                </div>
            </div>
        </div>
    </div>
</div>