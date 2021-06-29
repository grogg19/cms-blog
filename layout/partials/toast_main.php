<?php

/**
 * @var $pathToast
 * @var $toastMessage
 */

if (!empty($pathToast) && !empty($toastMessage)) { ?>
<section class="toast__container">
    <?php
    require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/' . $pathToast . '.php');
    ?>
</section>
<?php } ?>