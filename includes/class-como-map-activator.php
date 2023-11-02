<?php
/**
 * Fired during plugin activation
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Como_Map
 * @subpackage 	Como_Map/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 		1.0.0
 * @package 	Como_Map
 * @subpackage 	Como_Map/includes
 * @author 		Slushman <chris@slushman.com>
 */
class Como_Map_Activator {
	/**
	 * Declare custom post types, taxonomies, and plugin settings
	 * Flushes rewrite rules afterwards
	 *
	 * @since 		1.0.0
	 */
	public static function activate() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-como-map-admin.php';
		//Como_Map_Admin::new_cpt_map();
		//Como_Map_Admin::new_taxonomy_type();
		//flush_rewrite_rules();
		$opts 		= array();
		$options 	= Como_Map_Admin::get_options_list();
		foreach ( $options as $option ) {
			$opts[ $option[0] ] = $option[2];
		}
		update_option( 'como-map-options', $opts );
		Como_Map_Admin::add_admin_notices();
	} // activate()
} // class
