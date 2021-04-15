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
	                    <?php if(!empty($pages)) { ?>
		                    <div class="dx-separator"></div>
		                    <?php foreach ($pages as $page) { ?>
			                    <ul class="text-left mb-6 mt-6">
				                    <li><?= $page->getParameter('title') ?> url: <?= $page->getParameter('url') ?></li>
			                    </ul>
			                <?php } ?>
	                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
