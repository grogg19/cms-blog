<?php
/**
 * Шаблон Статической страницы
 */
/**
 * @var $pageParameters
 * @var $content
 * @var $user
 * @var $title
 */
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/header.php');
?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-12">
                <?php if(!empty($pageParameters)) { ?>
                <?= !empty($content) ? $content : '' ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/footer.php');
?>