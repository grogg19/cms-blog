<?php
/**
 * Шаблон
 * Редактирование поста
 */
?>
<div class="dx-box-5 pb-100 bg-grey-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7">
                <div class="dx-box dx-box-decorated">
                    <div class="dx-box-content">
                        <h2 class="h6 mb-6">Редактирование поста</h2>


                        <!-- START: Breadcrumbs -->
                        <ul class="dx-breadcrumbs text-left dx-breadcrumbs-dark mnb-6 fs-14">

                            <li><a href="help-center.html">Раздел администрирования</a></li>

                            <li>Редактирование поста</li>

                        </ul>
                        <!-- END: Breadcrumbs -->

                    </div>
                    <div class="dx-separator"></div>
                    <form class="dx-form" name="form_edit_post" id="form_edit_post" action="<?= (isset($form['action'])) ? $form['action'] : ""?>">
                        <input type="hidden" name="_token" value="<?=$token?>">

                        <div class="dx-box-content">
                            <input type="hidden" name="idPost" id="idPost" value="<?=$post->id?>">
                            <?php
                            (new \App\FormRenderer($form['fields']))->render($post);
                            ?>

                        </div>
                    </form>
                    <div class="dx-box-content pt-0">
                        <!-- START: Dropzone

                            Additional Attributes:
                            data-dropzone-action
                            data-dropzone-maxMB
                            data-dropzone-maxFiles
                        -->
                        <form class="dx-dropzone dz-started" enctype="multipart/form-data" action="/admin/blog/posts/img/upload" data-dropzone-maxMB="6" data-dropzone-maxFiles="6" multiple="">
                            <div class="dz-message">
                                <div class="dx-dropzone-icon">
                                    <span class="icon pe-7s-cloud-upload"></span>
                                </div>
                                <div class="h6 dx-dropzone-title">Drop files here or click to upload</div>
                                <div class="dx-dropzone-text">
                                    <p class="mnb-5 mnt-1">You can upload up to 5 files (maximum 5 MB each) of the following types: .jpg, .jpeg, .png, .zip.</p>
                                </div>
                            </div>
                        </form>
                        <div class="row justify-content-between vertical-gap dx-dropzone-attachment">
                            <div class="col-auto dx-dropzone-attachment-add">
                                <label class="mb-0" class="mnt-7"><span class="icon fas fa-paperclip mr-10"></span><span>Добавить изображение</span></label>
                            </div>
                            <div class="col-auto dx-dropzone-attachment-btn">
                                <button class="dx-btn dx-btn-lg" type="submit" name="button" id="save_button" data-form="form_edit_post">Сохранить</button>
                            </div>
                        </div>
                        <!-- END: Dropzone -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
