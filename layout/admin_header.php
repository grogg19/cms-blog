<?php
/**
 * User header
 */
use App\View;

(new View('base/header', ['title' => $title]))->render();

?>
<!-- START: Navbar -->
<nav class="dx-navbar dx-navbar-top dx-navbar-collapse dx-navbar-sticky dx-navbar-expand-lg dx-navbar-dropdown-triangle dx-navbar-autohide">
    <div class="container">

        <a href="/" class="dx-nav-logo">
            <img src="/layout/assets/images/logo.svg" alt="" width="88px">
        </a>

        <button class="dx-navbar-burger">
            <span></span><span></span><span></span>
        </button>

        <div class="dx-navbar-content">

            <ul class="dx-nav dx-nav-align-left">

                <li class="dx-drop-item">
                    <a href="/store.html">
                        Store
                    </a><ul class="dx-navbar-dropdown">

                        <li>
                            <a href="/store.html">
                                Store
                            </a>
                        </li>
                        <li>
                            <a href="/product.html">
                                Product
                            </a>
                        </li>
                        <li>
                            <a href="/checkout.html">
                                Checkout
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dx-drop-item active">
                    <a href="/blog.html">
                        Блог
                    </a><ul class="dx-navbar-dropdown">

                        <li class=" active">
                            <a href="/blog.html">
                                Блог
                            </a>
                        </li>
                        <li>
                            <a href="/admin/blog/posts/create">
                                Создать пост
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dx-drop-item">
                    <a href="/help-center.html">
                        Help Center
                    </a><ul class="dx-navbar-dropdown">

                        <li>
                            <a href="help-center.html">
                                Help Center
                            </a>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/documentations.html">
                                Documentations
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/documentations.html">
                                        Documentations
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-documentation.html">
                                        Single documentation
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/articles.html">
                                Knowledge Base
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/articles.html">
                                        Knowledge Base
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-article-category.html">
                                        Single Article Category
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-article.html">
                                        Single Article
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/forums.html">
                                Forums
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/forums.html">
                                        Forums
                                    </a>
                                </li>
                                <li>
                                    <a href="/topics.html">
                                        Topics
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-topic.html">
                                        Single Topic
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/ticket.html">
                                Ticket System
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/ticket.html">
                                        Ticket System
                                    </a>
                                </li>
                                <li>
                                    <a href="/ticket-submit.html">
                                        Submit Ticket
                                    </a>
                                </li>
                                <li>
                                    <a href="/ticket-submit-2.html">
                                        Submit Ticket 2
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-ticket.html">
                                        Single Ticket
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dx-drop-item">
                    <a href="/account.html">
                        Account
                    </a><ul class="dx-navbar-dropdown">

                        <li>
                            <a href="/account.html">
                                Account
                            </a>
                        </li>
                        <li>
                            <a href="/account-licenses.html">
                                Licenses
                            </a>
                        </li>
                        <li>
                            <a href="/account-settings.html">
                                Settings
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="dx-nav dx-nav-align-right">
                <?php if( session_status() == PHP_SESSION_ACTIVE && \Helpers\session()->get('authAuthorized') == 1) {
                    (new View('partials/admin_menu', ['user' => $user]))->render();
                } else { ?>
                    <li>
                        <a data-fancybox data-type="ajax" data-options='{"ajax" : {"settings" : {"type" : "post"}}  }' data-close-existing="true" data-touch="false" data-src="/login" data-filter="#block_login" href="javascript:;">Войти</a>
                    </li>
                    <li>
                        <span><a data-fancybox data-type="ajax" data-touch="false" data-close-existing="true" data-src="/signup" data-filter="#block_signup" href="javascript:;" class="dx-btn dx-btn-md dx-btn-transparent">Зарегистрироваться</a></span>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="dx-navbar dx-navbar-fullscreen">
    <div class="container">
        <button class="dx-navbar-burger">
            <span></span><span></span><span></span>
        </button>
        <div class="dx-navbar-content">

            <ul class="dx-nav dx-nav-align-left">

                <li class="dx-drop-item">
                    <a href="/store.html">
                        Store
                    </a><ul class="dx-navbar-dropdown">

                        <li>
                            <a href="/store.html">
                                Store
                            </a>
                        </li>
                        <li>
                            <a href="/product.html">
                                Product
                            </a>
                        </li>
                        <li>
                            <a href="/checkout.html">
                                Checkout
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dx-drop-item active">
                    <a href="/blog.html">
                        Blog
                    </a><ul class="dx-navbar-dropdown">

                        <li class=" active">
                            <a href="/blog.html">
                                Blog
                            </a>
                        </li>
                        <li>
                            <a href="/single-post.html">
                                Single Post
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dx-drop-item">
                    <a href="/help-center.html">
                        Help Center
                    </a><ul class="dx-navbar-dropdown">

                        <li>
                            <a href="/help-center.html">
                                Help Center
                            </a>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/documentations.html">
                                Documentations
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/documentations.html">
                                        Documentations
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-documentation.html">
                                        Single documentation
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/articles.html">
                                Knowledge Base
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/articles.html">
                                        Knowledge Base
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-article-category.html">
                                        Single Article Category
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-article.html">
                                        Single Article
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/forums.html">
                                Forums
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/forums.html">
                                        Forums
                                    </a>
                                </li>
                                <li>
                                    <a href="/topics.html">
                                        Topics
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-topic.html">
                                        Single Topic
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dx-drop-item">
                            <a href="/ticket.html">
                                Ticket System
                            </a><ul class="dx-navbar-dropdown">

                                <li>
                                    <a href="/ticket.html">
                                        Ticket System
                                    </a>
                                </li>
                                <li>
                                    <a href="/ticket-submit.html">
                                        Submit Ticket
                                    </a>
                                </li>
                                <li>
                                    <a href="/ticket-submit-2.html">
                                        Submit Ticket 2
                                    </a>
                                </li>
                                <li>
                                    <a href="/single-ticket.html">
                                        Single Ticket
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dx-drop-item">
                    <a href="/account.html">
                        Account
                    </a><ul class="dx-navbar-dropdown">

                        <li>
                            <a href="/account.html">
                                Account
                            </a>
                        </li>
                        <li>
                            <a href="/account-licenses.html">
                                Licenses
                            </a>
                        </li>
                        <li>
                            <a href="/account-settings.html">
                                Settings
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="dx-nav dx-nav-align-right">
                <?php if( session_status() == PHP_SESSION_ACTIVE && \Helpers\session()->get('authAuthorized') == 1) {
                    (new View('partials/admin_menu'))->render();
                } else { ?>
                    <li>
                        <a data-fancybox data-type="ajax" data-close-existing="true" data-touch="false" data-src="/login" data-filter="#block_login" href="javascript:;">Войти</a>
                    </li>
                    <li>
                        <span><a data-fancybox data-type="ajax" data-touch="false" data-close-existing="true" data-src="/signup" data-filter="#block_signup" href="javascript:;" class="dx-btn dx-btn-md dx-btn-transparent">Зарегистрироваться</a></span>
                    </li>
                <?php } ?>

            </ul>
        </div>
    </div>
</div>
<!-- END: Navbar -->

<div class="dx-main">

    <header class="dx-header dx-box-5">
        <div class="bg-image bg-image-parallax">
            <img src="/layout/assets/images/bg_top.jpg" class="jarallax-img" alt="">
            <div style="background-color: rgba(27, 27, 27, .6);"></div>
        </div>

        <div class="container mnb-8">
            <div class="row justify-content-center">
                <div class="col-xl-7">
                    <h1 class="h4 mb-10 text-white text-center">Раздел администрирования</h1>

                    <!-- START: Breadcrumbs -->
                    <ul class="dx-breadcrumbs text-center">

                        <li><a href="/index.html">Home</a></li>

                        <li>Blog</li>

                    </ul>
                    <!-- END: Breadcrumbs -->

                </div>
            </div>
        </div>
    </header>