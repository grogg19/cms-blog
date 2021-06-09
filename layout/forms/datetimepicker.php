<?php
/**
 * Шаблон элемента DATETIMEPICKER
 */
/**
 * @var $element
 * @var $valueAttribute
 */
?>
<div class="dx-form-group div-<?=key($element)?> form-element">
    <?php if($element[key($element)]['label']) { ?>
        <label for="<?=key($element)?>"><?=$element[key($element)]['label']?>:</label>
    <?php } ?>
    <input name="<?=key($element)?>" <?php if (isset($element[key($element)]['options'])) {
        foreach ($element[key($element)]['options'] as $key => $value) {
            echo ' ' .$key . '="' . $value . '" ';
        }
    }
    if (!empty($valueAttribute)) { ?>
           value="<?= $valueAttribute ?>"
           <?php } ?> />
</div>

