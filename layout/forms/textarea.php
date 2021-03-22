<?php
/**
 * Шаблон элемента TEXTAREA
 */
?>
                            <div class="dx-form-group div-<?=key($element)?> form-element">
                                <?php if($element[key($element)]['label']) { ?>
                                    <label for="<?=key($element)?>" ><?=$element[key($element)]['label']?>:</label>
                                <?php } ?>
                                    <textarea  name="<?=key($element)?>" <?php if (isset($element[key($element)]['options'])) {
                                        foreach ($element[key($element)]['options'] as $key => $value) {
                                            echo ' ' .$key . '="' . $value . '" ';
                                        }
                                    } ?> ><?php if (!empty($valueAttribute)) { ?><?=$valueAttribute?><?php } ?></textarea>
                            </div>
