<?php
/**
 * Список статических страниц - шаблон
 */
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-10">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
                        <h2 class="h6 mt-5 mb-5"><?= !empty($title) ? $title : ''?></h2>
	                    <div class="mt-10 mb-10">
		                    <a href="/admin/static-pages/add" ><i class="fas fa-plus"></i> Добавить страницу</a>
	                    </div>
	                    <?php if(!empty($pages)) { ?>
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
			                        <div class="col-1 text-center mb-6 mt-6"><a href="/admin/static-pages/delete" data-type="request" data-value="<?=$page->getFileName()['name']?>" data-action="/admin/static-pages/delete" title="Удалить страницу"><i class="fas fa-times"></i></a></div>
			                <?php } ?>
			                    </div>
		                    </form>
	                    <?php } ?>
	                    <div id="messageToast"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
