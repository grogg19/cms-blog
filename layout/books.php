<?php

use App\View;

(new View('header', ['title' => $title]))->render();

?>
<div class="container">
    <h1><?= (isset($title)) ? $title : "" ?></h1>
    <div class="row mb-3">
        <div class="col-6 themed-grid-col">
            <h3>Название</h3>
        </div>
        <div class="col-6 themed-grid-col">
            <h3>Автор</h3>
        </div>
    <?php
    foreach ($books as $book) { ?>
        <div class="col-6 themed-grid-col"><?=$book->name?></div>
        <div class="col-6 themed-grid-col"><?=$book->author?></div>
    <?php } ?>
    </div>
</div>
<?php
(new View('footer'))->render();
?>
