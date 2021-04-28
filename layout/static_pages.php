<?php
/**
 * Шаблон Статической страницы
 */

if(!empty($pageParameters) && $pageParameters['isHidden'] !== 0) { ?>
<?= !empty($content) ? $content : '' ?>
<?php } ?>

