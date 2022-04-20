<?php

/**
 * Template Name: Login
 */


get_header();

?>
<div id="content-vw">
    <div class="container">
        <div class="middle-align">
            <div class="loader-parent">
                <div class="loader"></div>
            </div>
            <section id="cb-login">
                <div class="row">

                    <div class="col-md-12 text-center">
                        <h5 class="vc_custom_heading section-title">Login</h5>
                    </div>
                    <div class="col-md-6 frm">
                        <div class="row">
                            <?php
                            require get_template_directory() . '/page-template/message-error.php';
                            require get_template_directory() . '/page-template/message-info.php';
                            ?>
                        </div>

                        <div class="cb-wrapper">
                            <form action="" method="post" name="frm-login" id="frm-login">
                            <?php wp_nonce_field('processLoginActionNonce', 'processLoginNonce'); ?>
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="username" placeholder="Username" required />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" placeholder="Password" required />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="submit" name="btn-cb-login" value="Login" id="btn-cb-login" class="btn-cb" class="btn btn-secondary">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php get_footer();
