<?php

/**
 * Template Name: Enduser
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

                ?>

                <div class="col-md-12">
                    <section id="cb-enduser">

    <h1>Hi i am end user</h1>

                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>