<?php
/**
 * Accessible search form.
 *
 * @package Marina
 */

$marina_search_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $marina_search_id ); ?>"><?php esc_html_e( 'Search for:', 'marina' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $marina_search_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'marina' ); ?>">
	<button type="submit"><?php esc_html_e( 'Search', 'marina' ); ?></button>
</form>
