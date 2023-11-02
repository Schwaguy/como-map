<?php
/**
 * The template for displaying all single maps posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Como_Map
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
/**
 * Get a custom header-employee.php file, if it exists.
 * Otherwise, get default header.
 */
get_header( 'map' );
if ( have_posts() ) :
	/**
	 * como-map-single-before-loop hook
	 *
	 * @hooked 		map_single_table_row_start 		10
	 */
	do_action( 'como-map-single-before-loop' );
	while ( have_posts() ) : the_post();
		include Como_Map_get_template( 'single-content' );
	endwhile;
	/**
	 * como-map-single-after-loop hook
	 *
	 * @hooked 		map_single_table_row_end 		90
	 */
	do_action( 'como-map-single-after-loop' );
endif;
get_footer( 'map' );