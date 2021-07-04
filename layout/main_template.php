<?php
/**
 * Шаблон вывода списка статей
 */

/**
 * @var array $posts
 * @var $token
 * @var string $imgPath
 * @var $user
 * @var $title
 */
if(session_status() !== 2 ) {
    require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/header.php');
} else {
    require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/admin_header.php');
}

?>
    <div class="dx-box-1 pb-100 bg-grey-6">
        <div class="container">
            <div class="row vertical-gap md-gap">
                <div class="col-lg-8">
                    <?= (!empty($content)) ? $content : 'Контент отсутствует'?>
                    <div id="messageToast"></div>
                </div>
                <div class="col-lg-4">
                    <div class="dx-sticky dx-sidebar" data-sticky-offsetTop="120" data-sticky-offsetBot="40">
                        <?php
                        /**
                         * Правый блок сайта
                         */
                        require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/section_right.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/footer.php');
?>