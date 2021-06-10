<?php
/**
 * Список статических страниц - шаблон
 */
/**
 * @var string $title
 * @var $pages
 * @var $token
 * @var $quantity
 */

use App\View;
use Illuminate\Pagination\LengthAwarePaginator;

?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-xl-12">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
	                    <div class="row align-items-center justify-content-between vertical-gap mnt-30 sm-gap mb-50">
		                    <div class="col-auto">
			                    <h2 class="h2 mb-0 mt-0"><?= $title ?></h2>
		                    </div>
		                    <div class="col pl-0 pr-0 d-none d-sm-block">
			                    <div class="dx-separator ml-10 mr-10"></div>
		                    </div>
	                    </div>
	                    <div class="mnt-30 mb-30">
		                    <a href="/admin/static-pages/add" ><i class="fas fa-plus"></i> Добавить страницу</a>
	                    </div>
                        <?php
                        // количество элементов на странице
                        (new View('partials.quantity_items', [
                            'quantity' => $quantity,
                            'items' => [10, 20, 50, 200, 'all']
                        ]))->render();
                        ?>
                        <?php if($pages->count() > 0) { ?>
		                    <div class="dx-separator"></div>
		                    <form class="dx-form" name="form_edit_static_pages" id="form_edit_static_pages" >
			                    <input type="hidden" name="_token" value="<?=(!empty($token) ? $token : '')?>">
			                    <div class="row mb-6 mt-6" >
				                    <div class="col-10 col-10"><b>Заголовок</b></div>
				                    <div class="col-1 col-1 text-center"><i class="fas fa-edit"></i></div>
				                    <div class="col-1 col-1 text-center"><i class="fas fa-trash-alt"></i></div>
				                </div>
			                    <div class="dx-separator"></div>
			                    <div class="row mb-6 mt-6" >
		                    <?php foreach ($pages as $page) { ?>
			                        <div class="col-10 mb-6 mt-6" >
				                        <p class="m-0"><i class="far fa-file-alt"></i> <?= $page->getParameter('title') ?></p>
				                        <p class="small op-7"><?= $page->getParameter('url'); ?></p>
			                        </div>
			                        <div class="col-1 text-center mb-6 mt-6"><a href="/admin/static-pages/edit" data-type="action" data-value="<?=$page->getFileName()['name']?>" data-action="/admin/static-pages/edit" title="Редактировать страницу"><i class="fas fa-pencil-alt"></i></a></div>
			                        <div class="col-1 text-center mb-6 mt-6"><a href="/admin/static-pages/delete" data-type="request" data-value="<?=$page->getFileName()['name']?>" data-action="/admin/static-pages/delete" title="Удалить страницу" data-field="pageName"><i class="fas fa-times"></i></a></div>
			                <?php } ?>
			                    </div>
		                    </form>
                            <?php
	                        // Пагинация
                            if($pages instanceof LengthAwarePaginator) {
                                (new View('partials.pagination', [
                                    'paginator' => $pages
                                ]))->render();
                            }
                            ?>
                        <?php } else { ?>
	                        <h5 class="mt-30">Список пользователей пуст</h5>
                        <?php } ?>
	                    <div id="messageToast"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
