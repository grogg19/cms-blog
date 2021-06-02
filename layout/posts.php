<?php
/**
 * Шаблон вывода списка статей
 */

use App\View;
use Illuminate\Database\Eloquent\Collection;

/**
 * @var array $posts
 * @var string $imgPath
 */

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
                    (new View('partials.posts_items', ['posts' => $posts , 'imgPath' => $imgPath ]))->render();
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
                    (new View('section_right'))->render();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>