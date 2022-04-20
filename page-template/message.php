<?php

/**
 * Template Name: CB Messages
 */

get_header(); ?>

<div id="content-vw">
    <div class="container">
        <div class="middle-align">
            <div class="loader-parent">
                <div class="loader"></div>
            </div>
            <section id="cb-messages">
                <div class="row">
                    <?php
                    require get_template_directory() . '/page-template/message-error.php';
                    require get_template_directory() . '/page-template/message-info.php';
                    ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php get_footer(); ?>