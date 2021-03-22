<?php
use App\View;

?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-8">
                <h1><?= (isset($message)) ? $message : "" ?></h1>
            </div>
            <div class="col-lg-4">
                <div class="dx-sticky dx-sidebar" data-sticky-offsetTop="120" data-sticky-offsetBot="40">

                    <?php
                    /**
                     * Правый блок сайта
                     */
                    (new View('section_right'))->render();
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
