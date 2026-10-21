<?php
/**
 * Search results.
 *
 * @package Marina
 */

get_header();
?>
<div class="wrap">
	<?php marina_breadcrumbs(); ?>
	<div class="content-area">
		<header class="entry-header">
			<h1>
				<?php
				/* translators: %s: search query */
				printf( esc_html__( 'Results for "%s"', 'marina' ), esc_html( get_search_query() ) );
				?>
			</h1>
			<?php get_search_form(); ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'class' => 'pagination' ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No results. Try different words.', 'marina' ); ?></p>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
