<?php
/**
 * User footer
 */
use App\View;

?>

    <!-- START: Footer -->
    <footer class="dx-footer">
        <div class="dx-box-1">
            <div class="container">
                <div class="row vertical-gap lg-gap">

                    <div class="col-sm-6 col-lg-3">

                        <div class="dx-widget-footer">
                            <div class="dx-widget-title">
                                <a href="index.html" class="dx-widget-logo">
                                    <img src="/layout/assets/images/logo.svg" alt="" width="88px">
                                </a>
                            </div>
                            <div class="dx-widget-text">
                                <p class="mb-0">&copy; <?=date('Y')?> <span class="dib">All rights reserved.</span> <span class="dib">Dexad &amp; nK.</span></p>
                            </div>
                            <div class="dx-widget-text">
                                <ul class="dx-social-links mnt-3">
                                    <li><a href="#"><span class="fab fa-behance-square"></span></a></li>
                                    <li><a href="#"><span class="fab fa-instagram"></span></a></li>
                                    <li><a href="#"><span class="fab fa-dribbble"></span></a></li>
                                    <li><a href="#"><span class="fab fa-github"></span></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">

                        <div class="dx-widget-footer">
                            <div class="dx-widget-title">
                                Useful Links
                            </div>
                            <ul class="dx-widget-list">
                                <li><a href="/store.html">Store</a></li>
                                <li><a href="/checkout.html">Checkout</a></li>
                                <li><a href="/forums.html">Forums</a></li>
                                <li><a href="/blog.html">Blog</a></li>
                            </ul>
                            <ul class="dx-widget-list">
                                <li><a href="/help-center.html">Help Center</a></li>
                                <li>
                                    <a data-fancybox data-touch="false" data-close-existing="true" data-src="#signup" href="javascript:;">Sign Up</a>
                                </li>
                                <li>
                                    <a data-fancybox data-touch="false" data-close-existing="true" data-src="#login" href="javascript:;">Log In</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">

                        <div class="dx-widget-footer">
                            <div class="dx-widget-title">
                                Latest Products
                            </div>
                            <div class="dx-widget-portfolio">
                                <a href="/product.html">Quantial – Moving Upon Signs Moveth Lesser</a>
                                <a href="/product.html">Sensific – Saying Beast Lesser for in Fruitful</a>
                                <a href="/product.html">Minist – Subdue Above for Signs Dry is Have Great</a>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6 col-lg-3">

                        <div class="dx-widget-footer">
                            <div class="dx-widget-title">
                                Latest Posts
                            </div>
                            <a href="/single-post.html" class="dx-widget-post">

        <span class="dx-widget-post-text">
            <span class="dx-widget-post-title">The Big Thing in Design</span>
            <span class="dx-widget-post-date">25 Sep 2018</span>
        </span>

                            </a>
                            <a href="/single-post.html" class="dx-widget-post">

        <span class="dx-widget-post-text">
            <span class="dx-widget-post-title">Will Coding Ever Rule the World?</span>
            <span class="dx-widget-post-date">24 Sep 2018</span>
        </span>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: Footer -->
</div>
<?php

(new View('base/footer'))->render();