<?php
/**
 * The template part for header
 *
 * @package VW Painter 
 * @subpackage vw_painter
 * @since VW Painter 1.0
 */
?>

<?php
  $vw_painter_search_hide_show = get_theme_mod( 'vw_painter_search_hide_show' );
  if ( 'Disable' == $vw_painter_search_hide_show ) {
   $colmd = 'col-lg-9 col-md-9';
  } else { 
   $colmd = 'col-lg-8 col-md-7';
  } 
?>
<div id="bottom-header">
<div class="container">
    <div class="row">
      <div class="<?php echo esc_html( $colmd ); ?>">
        <?php get_template_part( 'template-parts/header/navigation' ); ?>
      </div>	  	  	 <div class="col-lg-3 col-md-6"><div class="float-right"><?php dynamic_sidebar('social-widget'); ?></div>	 	    </div>	  
      <?php if ( 'Disable' != $vw_painter_search_hide_show ) {?>
        <div class="col-lg-1 col-md-2">
          <div class="search-box">
            <span><i class="fas fa-search"></i></span>
          </div>
        </div>
      <?php } ?>
    </div>
    <!---div class="serach_outer">
      <div class="closepop"><i class="far fa-window-close"></i></div>
      <div class="serach_inner">
        <?php //get_search_form(); ?>
      </div>
    </div-->
  </div>
</div>