<?php
/** Шаблон выпадающего списка "Количество записей" */
/**
 * @var $quantity
 * @var $quantityItems
 */
?>
<form method="get" action="">
    <div class="row align-items-center justify-content-between vertical-gap mnt-30 sm-gap mb-30">
        <span class="col-auto dx-form-group-inputs text-left">
            <label for="quantity" class="h6 mb-10 mt-0 ml-0">Количество записей на странице</label>
            <select name="quantity" id="quantity" class="col-10 custom-select form-control-sm form-control-style-2" data-field="perPage" data-method="changePerPage" onchange="submit()">
                <?php foreach ($quantityItems as $item) { ?>
                	<option value="<?= $item ?>" <?= $item == $quantity ? 'selected' : ''?>><?= $item == 'all' ? 'Все' : $item ?></option>
                <?php } ?>
            </select>
        </span>
    </div>
</form>