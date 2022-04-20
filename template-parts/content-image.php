<?php
/**
 * The template part for displaying image
 *
 * @package VW Painter 
 * @subpackage vw_painter
 * @since VW Painter 1.0
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
  <div class="post-main-box ">
    <div class="row m-0">
      <?php if(has_post_thumbnail()) {?>
        <div class="box-image col-lg-6 col-md-6">
          <?php the_post_thumbnail(); ?>
        </div>
      <?php } ?>
      <div class="new-text <?php if(has_post_thumbnail()) { ?>col-lg-6 col-md-6"<?php } else { ?>col-lg-12 col-md-12"<?php } ?>>
        <h3 class="section-title"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h3>
        <div class="post-info">
          <?php if(get_theme_mod('vw_painter_toggle_postdate',true)==1){ ?>
            <span class="entry-date"><?php echo get_the_date(); ?></span><span>|</span>
          <?php } ?>

          <?php if(get_theme_mod('vw_painter_toggle_author',true)==1){ ?>
            <span class="entry-author"> <?php the_author(); ?></span><span>|</span>
          <?php } ?>

          <?php if(get_theme_mod('vw_painter_toggle_comments',true)==1){ ?>
            <span class="entry-comments"><?php comments_number( __('0 Comment', 'vw-painter'), __('0 Comments', 'vw-painter'), __('% Comments', 'vw-painter') ); ?> </span>
          <?php } ?>
          <hr>
        </div>      
        <p><?php $excerpt = get_the_excerpt(); echo esc_html( vw_painter_string_limit_words( $excerpt, esc_attr(get_theme_mod('vw_painter_excerpt_number','30')))); ?></p>
        <div class="content-bttn">
          <a href="<?php echo esc_url( get_permalink() );?>" class="blogbutton-small" title="<?php esc_attr_e( 'READ MORE','vw-painter' ); ?>"><?php esc_html_e('READ MORE','vw-painter'); ?></a>
        </div>
      </div>
    </div>
  </div>
</div>