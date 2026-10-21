<?php
/**
 * Single page. Lists child pages under the content (e.g. the Services page).
 *
 * @package Marina
 */

get_header();
?>
<div class="wrap">
	<?php marina_breadcrumbs(); ?>
	<div class="content-area">
		<?php
		while ( have_posts() ) :
			the_post();
			$marina_children = get_pages(
				array(
					'child_of'    => get_the_ID(),
					'parent'      => get_the_ID(),
					'sort_column' => 'menu_order',
				)
			);
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<header class="entry-header">
					<h1><?php the_title(); ?></h1>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				<?php endif; ?>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>

			<?php if ( $marina_children ) : ?>
				<div class="grid" style="margin-top:2rem">
					<?php foreach ( $marina_children as $marina_child ) : ?>
						<article class="card">
							<h2 style="font-size:1.2rem"><a href="<?php echo esc_url( get_permalink( $marina_child ) ); ?>"><?php echo esc_html( get_the_title( $marina_child ) ); ?></a></h2>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $marina_child ), 22 ) ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</div>
<?php
get_footer();
