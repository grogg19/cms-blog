<?php
/**
 * Шаблон Статической страницы
 */
use App\View;

/**
 * @var $pageParameters
 * @var $content
 * @var $user
 * @var $title
 */
(new View('header', ['user' => $user ?: null, 'title' => $title]))->render();
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
<?php
(new View('footer'))->render();
?>