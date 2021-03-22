<?php
/**
 * Шаблон элемента INPUT
 */
?>
                            <div class="dx-form-group-md div-<?=key($element)?> form-element">
                                <?php if(isset($element[key($element)]['label'])) { ?>
                                    <label for="<?=key($element)?>" ><?=$element[key($element)]['label']?>:</label>
                                <?php } ?>
                            <input name="<?=key($element)?>"  <?php if (isset($element[key($element)]['options'])) {
                                foreach ($element[key($element)]['options'] as $key => $value) {
                                    echo ' ' . $key . '="' . $value . '" ';
                                }
                            }
                            if (!empty($valueAttribute) && $element[key($element)]['options']['type'] !== 'password') { ?>
                                value="<?= $valueAttribute ?>"
                            <?php } ?> />
                            </div>
