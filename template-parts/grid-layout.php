<?php
/**
 * The template part for displaying post
 *
 * @package VW Painter
 * @subpackage vw-painter
 * @since VW Painter 1.0
 */
?>
<div class="col-md-4 col-lg-4">
	<div id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
	    <div class="post-main-box">
	      	<div class="box-image">
	          	<?php 
		            if(has_post_thumbnail()) { 
		              the_post_thumbnail(); 
		            }
	          	?>
	        </div>
	        <h3 class="section-title"><?php the_title();?></h3>      
	        <div class="new-text">
	          	<p><?php $excerpt = get_the_excerpt(); echo esc_html( vw_painter_string_limit_words( $excerpt, esc_attr(get_theme_mod('vw_painter_excerpt_number','30')))); ?></p>
	        </div>
	        <div class="content-bttn">
		    	<a href="<?php echo esc_url( get_permalink() );?>" title="<?php esc_attr_e( 'Read More','vw-painter' ); ?>"><?php esc_html_e('READ MORE','vw-painter'); ?></a>
		    </div>
	    </div>
	    <div class="clearfix"></div>
  	</div>
</div>