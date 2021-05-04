<?php
/**
 * Список статических страниц - шаблон
 */
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-7">
                <div class="dx-box-decorated">
                    <div class="dx-box-content">
                        <h2 class="h6 mt-5 mb-5"><?= !empty($title) ? $title : ''?></h2>
<!--	                    <div class="mt-10 mb-10">-->
		                    <a href="/admin/static-pages/add" ><i class="fas fa-plus"></i></a>
<!--	                    </div>-->
	                    <?php if(!empty($pages)) { ?>
		                    <div class="dx-separator"></div>
		                    <?php foreach ($pages as $page) { ?>
			                    <ul class="text-left mb-6 mt-6">
				                    <li><a href="/admin/static-pages<?= $page->getParameter('url');?>/edit"><?= $page->getParameter('title') ?> url: <?= $page->getParameter('url') ?></a></li>
			                    </ul>
			                <?php } ?>
	                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
