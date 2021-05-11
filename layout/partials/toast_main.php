<?php
use App\View;

if (!empty($typeToast) && !empty($dataToast)) { ?>
<section class="toast__container">
    <?php
    (new View('partials.toasts.' . $typeToast, [
        'data' => $dataToast
    ]))->render();
    ?>
</section>
<?php } ?>