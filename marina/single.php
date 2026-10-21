<?php
/**
 * Single blog post.
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
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<header class="entry-header">
					<h1><?php the_title(); ?></h1>
					<p class="entry-meta">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						&middot; <?php the_author(); ?>
					</p>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				<?php endif; ?>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
			<?php
			the_post_navigation(
				array(
					'prev_text' => '&larr; %title',
					'next_text' => '%title &rarr;',
				)
			);
		endwhile;
		?>
	</div>
</div>
<?php
get_footer();
