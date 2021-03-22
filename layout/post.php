<?php
/**
 * Шаблон вывода одной статьи
 */
use App\View;

?>
<div class="dx-box-1 pb-100 bg-grey-6">
    <div class="container">
        <div class="row vertical-gap md-gap">
            <div class="col-lg-8">
                <div class="dx-blog-post dx-box dx-box-decorated">
                    <?php
                    if(!empty($post->images[0]->file_name)) {
                        ?>
                        <div class="dx-gallery pt-10 pr-10 pl-10">
                            <a href="<?=$imgPath . $post->images[0]->file_name?>" data-fancybox="images" class="dx-gallery-item">
                                <span class="dx-gallery-item-overlay"><span class="icon pe-7s-exapnd2"></span></span>
                                <img src="<?=$imgPath . $post->images[0]->file_name?>" class="dx-img" alt="">
                            </a>
                        </div>
                    <?php } ?>

                    <div class="dx-blog-post-box">
                        <h1 class="h3 dx-blog-post-title"><a href="single-post.html"><?= $post->title ?></a></h1>
                        <ul class="dx-blog-post-info">
                            <li>Дата публикации: <?= \Helpers\getDateTime($post->published_at) ?></li>
                            <li>Author: <a href="#">John</a></li>
                        </ul>
                    </div>
                    <div class="dx-blog-post-box">

                        <?= $post->content ?>
                    </div>

                    <?php
                    if(!empty($post->images)) {
                        ?>
                        <div class="dx-blog-post-box">
                            <div class="dx-gallery">
                                <div class="row vertical-gap">
                                    <?php foreach ($post->images as $key => $image) {

                                        if ( $key === 0 ) continue;

                                    ?>
                                    <div class="col-6">
                                        <a href="<?=$imgPath . $image->file_name?>" data-fancybox="images" class="dx-gallery-item">
                                            <span class="dx-gallery-item-overlay dx-gallery-item-overlay-md"><span class="icon pe-7s-exapnd2"></span></span>
                                            <img src="<?=$imgPath . $image->file_name?>" class="dx-img" alt="">
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="dx-separator"></div>
                    <div class="dx-blog-post-box pt-30 pb-30">
                        <ul class="dx-blog-post-tags mnt-6 mnb-1 db">
                            <li class="dx-blog-post-tags-title">Tags:</li>
                            <li><a href="#">Branding</a></li>
                            <li><a href="#">Design</a></li>
                            <li><a href="#">People</a></li>
                        </ul>

                    </div>
                </div>
                <div class="dx-box mt-55">
                    <h2 class="h4 mb-45">Комментарии:</h2>

                    <div class="dx-comment">
                        <div>
                            <div class="dx-comment-img">
                                <img src="/layout/assets/images/avatar-1.png" alt="">
                            </div>
                            <div class="dx-comment-cont">
                                <a href="#" class="dx-comment-name">John Leonard</a>
                                <a href="#" class="dx-comment-reply">Ответить</a>
                                <div class="dx-comment-text">
                                    <p class="mb-0">Nullam ac dui et purus malesuada gravida id fermentum orci. In eu ipsum quis urna hendrerit condimentum vitae a mauris. In congue turpis purus, vitae tempus ante id. Donec orci arcu, sagittis ut finibus vitae.</p>
                                </div>
                                <div class="dx-comment-date">12 Feb 2018 7:40 pm</div>
                            </div>
                        </div>
                        <div class="dx-comment">
                            <div>
                                <div class="dx-comment-img">
                                    <img src="/layout/assets/images/avatar-2.png" alt="">
                                </div>
                                <div class="dx-comment-cont">
                                    <a href="#" class="dx-comment-name">Mercy Shields</a>
                                    <a href="#" class="dx-comment-reply">Reply</a>
                                    <div class="dx-comment-text">
                                        <p class="mb-0">In at nunc sodales lorem blandit egestas. Suspendisse molestie fringilla purus, eget sagittis nunc ornare sit amet. Praesent vitae ligula eu massa rutrum hendrerit non facilisis nulla interdum ut.</p>
                                    </div>
                                    <div class="dx-comment-date">14 Feb 2018 8:29 am</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dx-comment">
                        <div>
                            <div class="dx-comment-img">
                                <img src="/layout/assets/images/avatar-3.png" alt="">
                            </div>
                            <div class="dx-comment-cont">
                                <a href="#" class="dx-comment-name">Maria Anthony</a>
                                <a href="#" class="dx-comment-reply">Reply</a>
                                <div class="dx-comment-text">
                                    <p class="mb-0">Donec libero sapien, dapibus id blandit sit amet, iaculis sed ligula. Morbi cursus maximus elementum. Phasellus viverra lacinia sapien sagittis tristique.</p>
                                </div>
                                <div class="dx-comment-date">18 Feb 2018 7:13 pm</div>
                            </div>
                        </div>
                    </div>

                    <form action="#" class="dx-form mt-50">
                        <div class="row vertical-gap">
                            <div class="col-md-6">
                                <input class="form-control form-control-style-3" type="text" placeholder="Имя">
                            </div>
                            <div class="col-md-6">
                                <input class="form-control form-control-style-3" type="text" placeholder="Вымя">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control form-control-style-3" name="name" rows="8" cols="80" placeholder="Ваш комментарий..."></textarea>
                            </div>
                            <div class="col-12">
                                <button type="button" name="button" class="dx-btn dx-btn-lg">Оставить комментарий</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dx-sticky dx-sidebar" data-sticky-offsetTop="120" data-sticky-offsetBot="40">
                    <?php
                    /**
                     * Правый блок сайта
                     */
                    (new View('section_right'))->render();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
