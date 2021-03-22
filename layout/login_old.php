<?php
/**
 * Шаблон аутентификации
 */
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7">
                <div class="dx-box dx-box-decorated">
                    <div id="block_login">
                        <div class="dx-box-content">
                            <h2 class="h6 mb-6">Авторизация</h2>
                        </div>
                        <div class="dx-separator"></div>
                        <form class="dx-form" name="form_login" id="form_login" method="post" action="<?= (isset($action)) ? $action : ""?>">
                            <input type="hidden" name="_token" value="<?=\Helpers\generateToken()?>">
                            <div class="dx-box-content">

                                <?php
                                (new \App\FormRenderer($fields))->render();
                                ?>

                            </div>

                        </form>
                        <div class="dx-box-content pt-0">
                            <button class="dx-btn dx-btn-lg" type="submit" name="button" id="save_button" data-form="form_login">Авторизоваться</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

