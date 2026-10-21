<?php
/**
 * Site footer with the business contact details (NAP: name, address, phone).
 *
 * Consistent NAP details on every page help local SEO.
 *
 * @package Marina
 */

$marina_phone = marina_business( 'business_phone' );
$marina_email = marina_business( 'business_email' );
?>
</main>

<section class="contact-strip" aria-label="<?php esc_attr_e( 'Contact', 'marina' ); ?>">
	<div class="wrap">
		<h2><?php esc_html_e( 'Need a quote for your next project?', 'marina' ); ?></h2>
		<a class="btn btn-primary" style="background:var(--ink);color:#fff" href="<?php echo esc_url( marina_cta_url() ); ?>"><?php echo esc_html( marina_business( 'hero_cta_text' ) ); ?></a>
	</div>
</section>

<footer class="site-footer">
	<div class="wrap">
		<div class="footer-cols">
			<div>
				<h2><?php bloginfo( 'name' ); ?></h2>
				<p><?php bloginfo( 'description' ); ?></p>
			</div>
			<div>
				<h2><?php esc_html_e( 'Contact us', 'marina' ); ?></h2>
				<address>
					<?php if ( marina_business( 'business_street' ) ) : ?>
						<?php echo esc_html( marina_business( 'business_street' ) ); ?><br>
					<?php endif; ?>
					<?php echo esc_html( marina_business( 'business_city' ) ); ?><br>
					<?php if ( $marina_phone ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $marina_phone ) ); ?>"><?php echo esc_html( $marina_phone ); ?></a><br>
					<?php endif; ?>
					<?php if ( $marina_email ) : ?>
						<a href="mailto:<?php echo esc_attr( $marina_email ); ?>"><?php echo esc_html( $marina_email ); ?></a>
					<?php endif; ?>
				</address>
			</div>
			<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer', 'marina' ); ?>">
				<h2><?php esc_html_e( 'Quick links', 'marina' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>
		</div>
		<p class="copyright">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
