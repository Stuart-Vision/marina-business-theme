<?php
/**
 * Post card used on the home page, blog and archives.
 *
 * @package Marina
 */

?>
<article <?php post_class( 'card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'marina-card' ); ?></a>
	<?php endif; ?>
	<p class="meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p><?php echo esc_html( get_the_excerpt() ); ?></p>
	<a class="more" href="<?php the_permalink(); ?>">
		<?php esc_html_e( 'Read more', 'marina' ); ?><span class="screen-reader-text"> <?php the_title(); ?></span>
	</a>
</article>
