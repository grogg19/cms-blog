<?php
/**
 * User footer
 */
use App\View;
use App\Controllers\PublicControllers\PublicPostController;
?>
    <!-- START: Footer -->

    <footer class="dx-footer">
        <div class="dx-box-1">
            <div class="container">
                <div class="row vertical-gap lg-gap">

                    <div class="col-sm-6 col-lg-3">

                        <div class="dx-widget-footer">
                            <div class="dx-widget-title">
                                <a href="/" class="dx-widget-logo">
                                    <img src="/layout/assets/images/logo.svg" alt="" width="88px">
                                </a>
                            </div>
                            <div class="dx-widget-text">
                                <p class="mb-0">&copy; <?=date('Y')?> <span class="dib">All rights reserved.</span> <span class="dib">Skillbox &amp; Co.</span></p>
                            </div>
							<?php (new View('partials.footer.social_buttons'))->render(); ?>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
	                <?php
                    (new View('partials.footer.footer_menu'))->render();
	                ?>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                        <?php
                        (new PublicPostController())->latestPosts('partials.footer.latest_posts');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: Footer -->
</div>
<?php

(new View('base.footer'))->render();