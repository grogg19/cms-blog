<?php
/**
 * Настройки СMS - шаблон
 */

use function Helpers\getDateTime;
/**
 * @var array $settings
 * @var string $token
 */
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-11">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
                        <div class="col pl-10 pr-10 d-none d-sm-block">
                            <div class="container">
                                <div class="row align-items-center justify-content-between vertical-gap mnt-20 sm-gap mb-30">
                                    <div class="col-auto">
                                        <h2 class="h4 mb-0 mt-0"><?= !empty($title) ? $title : ''?></h2>
                                    </div>
                                    <div class="col pl-10 pr-10 d-none d-sm-block">
                                        <div class="dx-separator ml-0 mr-0" id="settings-block"></div>
                                        <input type="hidden" name="_token" value="<?= $token ?>">
                                    </div>
                                </div>
                            </div>
                            <div id="messageToast"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>