<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package VW Painter
 */
?>

<div class="title-box">
    <div class="container">
        <h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'vw-painter' ); ?></h1>
    </div>
</div>

<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( esc_html__( 'Ready to publish your first post? Get started here.', 'vw-painter' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	<?php elseif ( is_search() ) : ?>
	<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'vw-painter' ); ?></p><br />
		<?php get_search_form(); ?>
	<?php else : ?>
	<p><?php esc_html_e( 'Dont worry&hellip it happens to the best of us.', 'vw-painter' ); ?></p><br />
	<div class="error-btn">
		<a href="<?php echo esc_url(home_url() ); ?>"><?php esc_html_e( 'Back to Home Page', 'vw-painter' ); ?></a>
	</div>
<?php endif; ?>