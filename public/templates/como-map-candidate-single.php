<?php
/**
 * Provide a public-facing view for a single post
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Como_Map
 * @subpackage 	Como_Map/public/partials
 */
$meta = get_post_custom( $item->ID );
$value = '';
if ( ! empty( $meta['map-location'][0] ) ) {
	$value = esc_attr( $meta['map-location'][0] );
}
//pretty( $meta );
?><div class="map-wrap">
	<p class="map-title"><a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>"><?php echo esc_html( $item->post_title ); ?></a></p>
</div>