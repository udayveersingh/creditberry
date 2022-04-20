<?php

/**
 * The template part for topbar
 *
 * @package VW Painter 
 * @subpackage vw_painter
 * @since VW Painter 1.0
 */
global $isFunnelLog;
$classLogo = $isFunnelLog ? "6" : "3";
$classMenu = $isFunnelLog ? "6" : "9";
?>
<div id="topbar">
	<div class="container">
		<div class="">
			<div class="row align-items-center">
				<div class="col-lg-<?php echo $classLogo; ?> col-md-<?php echo $classLogo; ?>  col align-self-start">
					<div class="logo">
						<?php
						
						if (has_custom_logo()) {
							vw_painter_the_custom_logo();
						} else { ?>
							<h1><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
							<?php $description = get_bloginfo('description', 'display');
								if ($description || is_customize_preview()) : ?> <p class="site-description"><?php echo esc_html($description); ?></p>
						<?php endif;
						} ?>
					</div>
					<div class="logo">
						<?php
						if ($isFunnelLog) {
							?>
							<a href="<?php echo site_url(); ?>" class="custom-logo-link" rel="home">
								<img class="white-lable-logo"src="<?php echo $isFunnelLog; ?>" class="custom-logo" alt="Credit Berry">
							</a>
						<?php
						}
						?>
					</div>
				</div>
				<!---div class="col-lg-4 col-md-6">
	        		<?php /*if(get_theme_mod('vw_painter_location') != ''){ ?>
						<i class="fas fa-location-arrow"></i><span><?php echo esc_html(get_theme_mod('vw_painter_location',''));?></span>
					<?php }*/ ?>
				</div-->
				<!---div class="col-lg-3 col-md-6 email">
	        		<?php /*if(get_theme_mod('vw_painter_email_address') != ''){ ?>
	        			<i class="fas fa-envelope-open"></i><span><?php echo esc_html(get_theme_mod('vw_painter_email_address',''));?></span>
	        		<?php }*/ ?>
	        	</div-->

				<div class="col-lg-<?php echo $classMenu; ?> col-md-<?php echo $classMenu; ?> ">
					<div class="top-head-right float-right"> <?php get_template_part('template-parts/header/navigation'); ?> <?php dynamic_sidebar('top-head-right'); ?> </div>
				</div>
			</div>
		</div>
	</div>
</div>