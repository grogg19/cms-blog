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
							<?php (new View('partials.footer.social_buttons'))->render(); ?>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
	                <?php
                    (new View('partials.footer.footer_menu'))->render();
	                ?>
                    </div>
<!--                    <div class="col-sm-6 col-lg-3">-->
<!--                    </div>-->
                    <div class="col-sm-12 col-lg-6">
                        <?php
                        (new View('partials.footer.latest_posts', (new \App\Controllers\PublicControllers\PublicPostController())->latestPosts()))->render();
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