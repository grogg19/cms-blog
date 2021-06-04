<?php
/**
 * Шаблон элемента EDITOR
 */

/**
 * @var $element
 * @var $valueAttribute
 */
?>
<div class="dx-form-group div-<?=key($element)?> form-element mt-20">
    <?php if($element[key($element)]['label']) { ?>
        <label for="form_editor" ><?=$element[key($element)]['label']?>:</label>
    <?php } ?>
    <div class="dx-editor-quill">
        <div id="form_editor" <?php if (isset($element[key($element)]['options'])) {
            foreach ($element[key($element)]['options'] as $key => $value) {
                echo ' ' .$key . '="' . $value . '" ';
            }
        } ?> ><?php if (!empty($valueAttribute)) { ?><?=$valueAttribute?>
            <?php } ?></div>
    </div>
</div>

