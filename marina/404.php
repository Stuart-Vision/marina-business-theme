<?php
/**
 * Page not found.
 *
 * @package Marina
 */

get_header();
?>
<div class="wrap">
	<div class="content-area entry">
		<h1><?php esc_html_e( 'Page not found', 'marina' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for has moved or no longer exists. Try a search, or go back to the home page.', 'marina' ); ?></p>
		<?php get_search_form(); ?>
		<p style="margin-top:1.5rem"><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'marina' ); ?></a></p>
	</div>
</div>
<?php
get_footer();
