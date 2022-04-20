<?php

/**
 * Template Name: Thank You
 */

get_header(); ?>

<div id="content-vw">
    <div class="container">
        <div class="middle-align">
            <div class="loader-parent">
                <div class="loader"></div>
            </div>
            <div class="row">
                <?php
                require get_template_directory() . '/page-template/message-error.php';
                require get_template_directory() . '/page-template/message-info.php';
                require get_template_directory() . '/page-template/message-success.php';

                if (isset($_SESSION['thankyou']['ref']) && !empty($_SESSION['thankyou']['ref'])) {
                    ?>
                    <div class="main-box clearfix">
                        <div class="main-box-body clearfix">
                            <h3>Thank you for your interest</h3>
                            <?php if ($_SESSION['thankyou']['ref'] == 'login') : ?>
                                <p>Click below link to get started on our new portal.</p>
                            <?php else : ?>
                                <p>Please check your email for more information</p>
                                <p>Or</p>
                                <p>Log into your account on our new portal
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="main-box-footer">
                            <a class="btn btn-primary" href="<?php echo $_SESSION['thankyou']['redirect']; ?>">Get started</a>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>