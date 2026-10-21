<?php
/**
 * Blog, archives and the fallback for every other view.
 *
 * @package Marina
 */

get_header();
?>
<div class="wrap">
	<?php marina_breadcrumbs(); ?>
	<div class="content-area">
		<header class="entry-header">
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<h1><?php single_post_title(); ?></h1>
			<?php elseif ( is_archive() ) : ?>
				<?php the_archive_title( '<h1>', '</h1>' ); ?>
				<?php the_archive_description( '<div class="entry-meta">', '</div>' ); ?>
			<?php else : ?>
				<h1><?php esc_html_e( 'Latest articles', 'marina' ); ?></h1>
			<?php endif; ?>
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
			<?php
			the_posts_pagination(
				array(
					'mid_size' => 1,
					'class'    => 'pagination',
				)
			);
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found here yet.', 'marina' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
