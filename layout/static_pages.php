<?php
/**
 * Шаблон Статической страницы
 */
?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-12">
                <?php if(!empty($pageParameters) && $pageParameters['isHidden'] !== 0) { ?>
                <?= !empty($content) ? $content : '' ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>