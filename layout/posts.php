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
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/header.php');
?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-8">
	            <div id="list-posts">
                    <?php
                    /**
                     * Блок постов
                     */
                    require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/posts_items.php');
                    ?>
	            </div>
                <div class="dx-blog-item pt-0 mt-30">
                    <a href="#" class="dx-btn dx-btn-lg dx-btn-grey dx-btn-block dx-btn-load btn-more-posts" data-value="1">Показать ещё</a>
                </div>
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