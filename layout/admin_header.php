<?php
/**
 * User header
 */

/**
 * @var $user
 * @var $title
 */

require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/base/admin_header.php');

?>
<!-- START: Navbar -->
<nav class="dx-navbar dx-navbar-top dx-navbar-collapse dx-navbar-sticky dx-navbar-expand-lg dx-navbar-dropdown-triangle dx-navbar-autohide">
    <div class="container" style="height: 100%">

        <a href="/" class="dx-nav-logo">
            <img src="/layout/assets/images/logo.svg" alt="" width="88px">
        </a>

        <button class="dx-navbar-burger">
            <span></span><span></span><span></span>
        </button>

        <div class="dx-navbar-content">
            <?php if(!empty($user) && in_array($user->role->code, ['admin','content-manager'])) {
                require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/header/main_admin_menu.php');
            } else {
                require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/header/main_public_menu.php');
            } ?>
            <ul class="dx-nav dx-nav-align-right">
                <?php if(!empty($user)) {
                    require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/header/admin_menu.php');
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
    <div class="container" style="height: 100%">
        <button class="dx-navbar-burger">
            <span></span><span></span><span></span>
        </button>
        <div class="dx-navbar-content">
            <?php if(!empty($user) && in_array($user->role->code, ['admin'])) {
                require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/header/main_admin_menu.php');
            } else {
                require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/header/main_public_menu.php');
            } ?>
            <ul class="dx-nav dx-nav-align-right">
                <?php if(!empty($user)) {
                    require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/header/admin_menu.php');
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
                </div>
            </div>
        </div>
    </header>