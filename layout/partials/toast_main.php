<?php
use App\View;
?>
<h1>Toasts</h1>
<div class="toast__actions">
    <button class="toast__trigger">Info Toast</button>
    <button class="toast__trigger">Warning Toast</button>
    <button class="toast__trigger">Success Toast</button>
</div>

<section class="toast__container">

<?php
    (new View('partials.toasts.info', [
        'title' => (!empty($title)) ? $title : ''
    ]))->render();

    (new View('partials.toasts.warning', [
        'title' => (!empty($title)) ? $title : ''
    ]))->render();

    (new View('partials.toasts.success', [
        'title' => (!empty($title)) ? $title : ''
    ]))->render();
?>


</section>