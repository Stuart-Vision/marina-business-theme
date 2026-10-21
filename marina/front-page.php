<?php
/**
 * Home page: hero, services (child pages of "Services") and latest articles.
 *
 * @package Marina
 */

get_header();

$marina_services_page = get_page_by_path( 'services' );
$marina_services      = $marina_services_page
	? get_pages(
		array(
			'child_of'    => $marina_services_page->ID,
			'parent'      => $marina_services_page->ID,
			'sort_column' => 'menu_order',
		)
	)
	: array();
$marina_latest        = new WP_Query(
	array(
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>

<section class="hero">
	<div class="wrap">
		<h1><?php bloginfo( 'name' ); ?></h1>
		<p><?php bloginfo( 'description' ); ?></p>
		<div class="actions">
			<a class="btn btn-primary" href="<?php echo esc_url( marina_cta_url() ); ?>"><?php echo esc_html( marina_business( 'hero_cta_text' ) ); ?></a>
			<?php if ( $marina_services_page ) : ?>
				<a class="btn btn-ghost" href="<?php echo esc_url( get_permalink( $marina_services_page ) ); ?>"><?php esc_html_e( 'Our services', 'marina' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $marina_services ) : ?>
	<section class="section" aria-labelledby="services-title">
		<div class="wrap">
			<div class="section-head">
				<h2 id="services-title"><?php echo esc_html( get_the_title( $marina_services_page ) ); ?></h2>
				<?php if ( has_excerpt( $marina_services_page ) ) : ?>
					<p><?php echo esc_html( get_the_excerpt( $marina_services_page ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="grid">
				<?php foreach ( $marina_services as $marina_service ) : ?>
					<article class="card">
						<h3><a href="<?php echo esc_url( get_permalink( $marina_service ) ); ?>"><?php echo esc_html( get_the_title( $marina_service ) ); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $marina_service ), 22 ) ); ?></p>
						<a class="more" href="<?php echo esc_url( get_permalink( $marina_service ) ); ?>">
							<?php esc_html_e( 'Learn more', 'marina' ); ?><span class="screen-reader-text"> <?php echo esc_html( get_the_title( $marina_service ) ); ?></span>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( $marina_latest->have_posts() ) : ?>
	<section class="section section-soft" aria-labelledby="latest-title">
		<div class="wrap">
			<div class="section-head">
				<h2 id="latest-title"><?php esc_html_e( 'Guides and news', 'marina' ); ?></h2>
			</div>
			<div class="grid">
				<?php
				while ( $marina_latest->have_posts() ) :
					$marina_latest->the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
