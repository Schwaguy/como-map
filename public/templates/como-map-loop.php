<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the archive loop.
 *
 * @link       http://slushman.com
 * @since      1.0.0
 *
 * @package    Como_Map
 * @subpackage Como_Map/public/partials
 */
if (isset($items)) {
	/**
	 * como-map-before-loop hook
	 *
	 * @hooked 		table_wrap_start 		10
	 */
	do_action( 'como-map-before-loop' );
	foreach ( $items as $item ) {
		$meta = get_post_custom( $item->ID );
		/**
		 * como-map-before-loop-content hook
		 *
		 * @param 		object  	$item 		The post object
		 *
		 * @hooked 		table_row_start 		10
		 */
		do_action( 'como-map-before-loop-content', $item, $meta );
			/**
			 * como-map-loop-content hook
			 *
			 * @param 		object  	$item 		The post object
			 *
			 * @hooked 		content_map_row 		10
			 * @hooked 		content_map_location 	15
			 */
			do_action( 'como-map-loop-content', $item, $meta );
		/**
		 * como-map-after-loop-content hook
		 *
		 * @param 		object  	$item 		The post object
		 *
		 * @hooked 		content_link_end 		10
		 * @hooked 		table_row_end 		90
		 */
		do_action( 'como-map-after-loop-content', $item, $meta );
	} // foreach
	/**
	 * como-map-after-loop hook
	 *
	 * @hooked 		table_wrap_end 			10
	 */
	do_action( 'como-map-after-loop' );
}