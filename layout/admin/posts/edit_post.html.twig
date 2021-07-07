<?php
/**
 * Шаблон
 * Редактирование поста
 */

/**
 * @var $token
 * @var $form
 * @var $post
 * @var $imgConfig
 * @var $user;
 * @var $title
 */

require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/admin_header.php');
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
                            <li><a href="/admin/blog/posts">Список постов</a></li>
                            <li>Редактирование поста</li>
                        </ul>
                        <!-- END: Breadcrumbs -->
                    </div>
                    <div class="dx-separator"></div>
                    <form class="dx-form" name="form_edit_post" id="form_edit_post" action="<?= (isset($form['action'])) ? $form['action'] : ""?>">
                        <input type="hidden" name="_token" value="<?= !empty($token) ? $token : ''?>">
                        <div class="dx-box-content">
                            <input type="hidden" name="idPost" id="idPost" value="<?=!empty($post->id) ? $post->id : ''?>">
                            <?php if(!empty($formFields)) {?>
                                <?= htmlspecialchars_decode($formFields) ?>
                            <?php } ?>
                        </div>
                    </form>
                    <div class="dx-box-content pt-0">
                        <!-- START: Dropzone
                            Additional Attributes:
                            data-dropzone-action
                            data-dropzone-maxMB
                            data-dropzone-maxFiles
                        -->
                        <form class="dx-dropzone dz-started" enctype="multipart/form-data" action="/admin/blog/posts/img/upload" data-dropzone-maxMB="<?= $imgConfig['maxImageSize'] ?>" data-dropzone-maxFiles="<?= $imgConfig['maxFilesAtOnce'] ?>" multiple="">
                            <div class="dz-message">
                                <div class="dx-dropzone-icon">
                                    <span class="icon pe-7s-cloud-upload"></span>
                                </div>
                                <div class="h6 dx-dropzone-title">Перетащите картинки сюда или нажмите "Добавить изображение"</div>
                                <div class="dx-dropzone-text">
                                    <p class="mnb-5 mnt-1">Вы можете загрузить до <?= $imgConfig['maxFilesAtOnce'] ?> файлов (максимальный вес <?= $imgConfig['maxImageSize'] ?> MB каждый) следующих типов: .jpg, .jpeg, .png</p>
                                </div>
                            </div>
                        </form>
                        <div class="row justify-content-between vertical-gap dx-dropzone-attachment">
                            <div class="col-auto dx-dropzone-attachment-add">
                                <label class="mb-0 mnt-7"><span class="icon fas fa-paperclip mr-10"></span><span>Добавить изображение</span></label>
                            </div>
                            <div class="col-auto dx-dropzone-attachment-btn">
                                <button class="dx-btn dx-btn-lg" type="submit" name="button" id="save_button" data-form="form_edit_post">Сохранить</button>
                            </div>
                        </div>
                        <!-- END: Dropzone -->
	                    <div id="messageToast"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/footer.php');
?>