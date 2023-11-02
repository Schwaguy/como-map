<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Como_Map
 * @subpackage 	Como_Map/admin
 */
/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package 	Como_Map
 * @subpackage 	Como_Map/admin
 * @author 		Slushman <chris@slushman.com>
 */
class Como_Map_Admin {
	/**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;
	/**
	 * The ID of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.0
	 * @param 		string 			$Como_Map 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->set_options();
	}
	/**
     * Adds notices for the admin to display.
     * Saves them in a temporary plugin option.
     * This method is called on plugin activation, so its needs to be static.
     */
    public static function add_admin_notices() {
    	$notices 	= get_option( 'Como_Map_deferred_admin_notices', array() );
  		//$notices[] 	= array( 'class' => 'updated', 'notice' => esc_html__( 'Como map: Custom Activation Message', 'como-map' ) );
  		//$notices[] 	= array( 'class' => 'error', 'notice' => esc_html__( 'Como map: Problem Activation Message', 'como-map' ) );
  		apply_filters( 'Como_Map_admin_notices', $notices );
  		update_option( 'Como_Map_deferred_admin_notices', $notices );
    } // add_admin_notices
	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/Administration_Menus
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu() {
		// Top-level page
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		// Submenu Page
		// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_submenu_page(
			'edit.php?post_type=map',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Como Map Settings', 'como-map' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'como-map' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);
		add_submenu_page(
			'edit.php?post_type=map',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Como Map Help', 'como-map' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Help', 'como-map' ) ),
			'manage_options',
			$this->plugin_name . '-help',
			array( $this, 'page_help' )
		);
	} // add_menu()
	/**
     * Manages any updates or upgrades needed before displaying notices.
     * Checks plugin version against version required for displaying
     * notices.
     */
	public function admin_notices_init() {
		$current_version = '1.0.0';
		if ( $this->version !== $current_version ) {
			// Do whatever upgrades needed here.
			update_option('my_plugin_version', $current_version);
			$this->add_notice();
		}
	} // admin_notices_init()
	/**
	 * Displays admin notices
	 *
	 * @return 	string 			Admin notices
	 */
	public function display_admin_notices() {
		$notices = get_option( 'Como_Map_deferred_admin_notices' );
		if ( empty( $notices ) ) { return; }
		foreach ( $notices as $notice ) {
			echo '<div class="' . esc_attr( $notice['class'] ) . '"><p>' . $notice['notice'] . '</p></div>';
		}
		delete_option( 'Como_Map_deferred_admin_notices' );
    } // display_admin_notices()
	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/como-admin.css', array(), $this->version, 'all' );
	} // enqueue_styles()
	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {
		global $post_type;
		$screen = get_current_screen();
		if ( 'map' === $post_type || $screen->id === $hook_suffix ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/como-file-uploader.min.js', array( 'jquery' ), $this->version, true );
			
			//wp_enqueue_script( $this->plugin_name . '-repeater', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-repeater.min.js', array( 'jquery' ), $this->version, true );
			
			wp_enqueue_script( 'jquery-ui-datepicker' );
			$localize['repeatertitle'] = __( 'File Name', 'como-map' );
			wp_localize_script( 'como-map', 'nhdata', $localize );
		}
	} // enqueue_scripts()
	/**
	 * Creates a checkbox field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_checkbox( $args ) {
		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;
		apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-checkbox.php' );
	} // field_checkbox()
	/**
	 * Creates an editor field
	 *
	 * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_editor( $args ) {
		$defaults['description'] 	= '';
		$defaults['settings'] 		= array( 'textarea_name' => $this->plugin_name . '-options[' . $args['id'] . ']' );
		$defaults['value'] 			= '';
		apply_filters( $this->plugin_name . '-field-editor-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-editor.php' );
	} // field_editor()
	/**
	 * Creates a set of radios field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_radios( $args ) {
		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;
		apply_filters( $this->plugin_name . '-field-radios-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-radios.php' );
	} // field_radios()
	public function field_repeater( $args ) {
		$defaults['class'] 			= 'repeater';
		$defaults['fields'] 		= array();
		$defaults['id'] 			= '';
		$defaults['label-add'] 		= 'Add Item';
		$defaults['label-edit'] 	= 'Edit Item';
		$defaults['label-header'] 	= 'Item Name';
		$defaults['label-remove'] 	= 'Remove Item';
		$defaults['title-field'] 	= '';
/*
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
*/
		apply_filters( $this->plugin_name . '-field-repeater-options-defaults', $defaults );
		$setatts 	= wp_parse_args( $args, $defaults );
		$count 		= 1;
		$repeater 	= array();
		if ( ! empty( $this->options[$setatts['id']] ) ) {
			$repeater = maybe_unserialize( $this->options[$setatts['id']][0] );
		}
		if ( ! empty( $repeater ) ) {
			$count = count( $repeater );
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-repeater.php' );
	} // field_repeater()
	/**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_select( $args ) {
		$defaults['aria'] 			= '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= 'widefat';
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['selections'] 	= array();
		$defaults['value'] 			= '';
		apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {
			$atts['aria'] = $atts['description'];
		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {
			$atts['aria'] = $atts['label'];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-select.php' );
	} // field_select()
	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text( $args ) {
		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';
		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-text.php' );
	} // field_text()
	
	/**
	 * Creates a number field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_number( $args ) {
		$defaults['class'] 			= 'number';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'number';
		$defaults['min'] 			= '0';
		$defaults['max'] 			= 'max';
		$defaults['step'] 			= '0.01';
		$defaults['value'] 			= '';
		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-number.php' );
	} // field_text()
	
	/**
	 * Creates a Color Picker field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_colorpicker( $args ) {
		$defaults['class'] 			= 'text colorpicker';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';
		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-colorpicker.php' );
	} // field_text()
	/**
	 * Creates a textarea field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_textarea( $args ) {
		$defaults['class'] 			= 'large-text';
		$defaults['cols'] 			= 50;
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['rows'] 			= 10;
		$defaults['value'] 			= '';
		apply_filters( $this->plugin_name . '-field-textarea-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/como-admin-field-textarea.php' );
	} // field_textarea()
	
	/**
	 * Creates a Image Upload field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_image_upload( $args ) {
		$defaults['class'] 				= 'widefat url-file';
		//$defaults['id'] 				= '';
		$defaults['label-add'] 			= 'Add Image';
		$defaults['label-edit'] 		= 'Edit Image';
		$defaults['label-header'] 		= 'Image Name';
		$defaults['label-remove'] 		= 'Remove Image';
		$defaults['label-upload'] 		= 'Choose/Upload Image';
		$defaults['name'] 				= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 		= '';
		$defaults['type'] 				= 'hidden';
		$defaults['value'] 				= '';
		
		apply_filters( $this->plugin_name . '-field-image-options-defaults', $defaults );
		
		$atts = wp_parse_args( $args, $defaults );
		if ( ! empty( $this->options[$atts['id']] ) ) {
			$atts['value'] = $this->options[$atts['id']];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-image-upload.php' );
	} // field_image_upload()
	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {
		$options = array();
		$options[] = array( 'message-no-openings', 'text', 'Thank you for your interest! There are no maps at this time.' );
		$options[] = array('map-columns','repeater', array(array('column-name','text'), array('column-abbreviation','text'), array('column-id','text'), array('column-width','number'), array('column-type','select') ) );
		return $options;
	} // get_options_list()
	/**
	 * Adds links to the plugin links row
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of row links
	 * @param 		string 		$file 		The name of the file
	 * @return 		array 					The modified array of row links
	 */
	public function link_row( $links, $file ) {
		if ( Como_Map_FILE === $file ) {
			$links[] = '<a href="https://www.facebook.com/ComoCreative">Facebook</a>';
		}
		return $links;
	} // link_row()
	/**
	 * Adds a link to the plugin settings page
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function link_settings( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'edit.php?page=' . $this->plugin_name . '-settings' ) ), esc_html__( 'Settings', 'como-map' ) );
		return $links;
	} // link_settings()
	/**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	/*public static function new_cpt_map() {
		$cap_type 	= 'post';
		$plural 	= 'Maps';
		$single 	= 'Map';
		$cpt_name 	= 'map';
		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= FALSE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= TRUE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-chart-bar';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array('title','page-attributes','revisions');
		$opts['taxonomies']								= array();
		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";
		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'como-map' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'como-map' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'como-map' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'como-map' );
		$opts['labels']['menu_name']					= esc_html__( 'map', 'como-map' );
		$opts['labels']['name']							= esc_html__( $plural, 'como-map' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'como-map' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'como-map' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'como-map' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'como-map' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'como-map' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'como-map' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'como-map' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'como-map' );
		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'como-map' );
		$opts['rewrite']['with_front']					= FALSE;
		$opts = apply_filters( 'como-map-cpt-options', $opts );
		register_post_type( strtolower( $cpt_name ), $opts );
		
		
		// Add Columns to Client Admin Screen
		add_filter( 'manage_'. $cpt_name .'_posts_columns', 'set_custom_edit_map_columns', 1);
		function set_custom_edit_map_columns($columns) {
			unset($columns['title']);
			unset($columns['date']);
			unset($columns['wps_post_thumbs']);
			unset($columns['taxonomy-deal-type']);
			$columns['title'] = __('Map', 'como-map');
			$columns['map-progress'] = __('Progress', 'como-map');
			$columns['map-class'] = __('Class', 'como-map');
			$columns['map-link'] = __('Link', 'como-map');
			$columns['map-progress-text'] = __('Progress Text', 'como-map');
			
			$custCols = getCustomFields();
			$custCount = ((is_array($custCols)) ? count($custCols) : 0);
			if ($custCount > 0) {
				for ($c=0;$c<$custCount;$c++) {
					if (($custCols[$c][3] != 'title-column') && ($custCols[$c][3] != 'progress-column')) {
						if ($custCols[$c][1] != 'title') {
							$columns[$custCols[$c][0]] = __($custCols[$c][2], 'como-map');
						} 
					}
				}
			}
			$columns['date'] = __( 'Date Published', 'como-map' ); 
			return $columns;
		}
		// Add the data to the custom columns for the post type:
		add_action( 'manage_'. $cpt_name .'_posts_custom_column' , 'custom_map_column', 1, 2 );
		function custom_map_column( $column, $post_id ) {
			
			$custCols = array();
			$custCols[] = array('map-progress','number','Progress','text-column'); 
			$custCols[] = array('map-class','text','Class','text-column'); 
			$custCols[] = array('map-link','text','Link','text-column'); 
			$custCols[] = array('map-progress-text','text','Progress Text','text-column'); 
 			$custCols = getCustomFields($custCols);
			$custCount = ((is_array($custCols)) ? count($custCols) : 0);
			
			if ($custCount > 0) {
				for ($c=0; $c<$custCount; $c++) {
					$key = true;
					do {
						if ($column == $custCols[$c][0]) {
							if ($custCols[$c][3] == 'title-column') {
								echo 'title'; 
								$key = false;
							} elseif ($custCols[$c][3] == 'progress-column') {
								echo 'progress'; 
								$key = false;
							} elseif ($custCols[$c][0] == 'map-progress') {
								echo '<div class="progress"><div class="progress-bar" role="progressbar" style="width: '. get_post_meta($post_id ,$custCols[$c][0], true) .'%" aria-valuenow="'. get_post_meta($post_id ,$custCols[$c][0], true) .'" aria-valuemin="0" aria-valuemax="100">'. get_post_meta($post_id ,$custCols[$c][0], true) .'%</div></div>'; 
								$key = false;
							} elseif ($custCols[$c][1] == 'image') {
								if (!empty($custCols[$c][0])) {
									$imgID = get_post_meta($post_id ,$custCols[$c][0], true);
									echo wp_get_attachment_image($imgID, 'thumbnail', '', array('class'=>'img-responsive img-fluid'));
								}
								$key = false;
							} else {
								echo get_post_meta($post_id ,$custCols[$c][0], true);
								$key = false; 						
							}
						} else {
							$key = false;
						}
					 } while ($key);
				}
			}
		}
	} // new_cpt_map()*/
	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	/*public static function new_taxonomy_type() {
		$plural 	= 'Types';
		$single 	= 'Type';
		$tax_name 	= 'map_type';
		$opts['hierarchical']							= TRUE;
		//$opts['meta_box_cb'] 							= '';
		$opts['public']									= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['show_admin_column'] 						= FALSE;
		$opts['show_in_nav_menus']						= TRUE;
		$opts['show_tag_cloud'] 						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['sort'] 									= '';
		//$opts['update_count_callback'] 					= '';
		$opts['capabilities']['assign_terms'] 			= 'edit_posts';
		$opts['capabilities']['delete_terms'] 			= 'manage_categories';
		$opts['capabilities']['edit_terms'] 			= 'manage_categories';
		$opts['capabilities']['manage_terms'] 			= 'manage_categories';
		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'como-map' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'como-map' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'como-map' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'como-map' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'como-map');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'como-map' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'como-map' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'como-map' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'como-map' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'como-map' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'como-map' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'como-map' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'como-map' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'como-map' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'como-map' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'como-map' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'como-map' );
		$opts['rewrite']['ep_mask']						= EP_NONE;
		$opts['rewrite']['hierarchical']				= FALSE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $tax_name ), 'como-map' );
		$opts['rewrite']['with_front']					= FALSE;
		$opts = apply_filters( 'como-map-taxonomy-options', $opts );
		register_taxonomy( $tax_name, 'map', $opts );
	} // new_taxonomy_type()*/
	/**
	 * Creates the help page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_help() {
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-page-help.php' );
	} // page_help()
	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_options() {
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-page-settings.php' );
	} // page_options()
	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {
		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'message-no-openings',
			apply_filters( $this->plugin_name . 'label-message-no-openings', esc_html__( 'No map Message', 'como-map' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-messages',
			array(
				'description' 	=> 'This message displays on the page if no map postings are found.',
				'id' 			=> 'message-no-openings',
				'value' 		=> 'Thank you for your interest! There are no map openings at this time.',
			)
		);
		add_settings_field(
			'map-columns',
			apply_filters( $this->plugin_name . 'label-map-columns', esc_html__( 'map Columns', 'como-map' ) ),
			array( $this, 'field_repeater' ),
			$this->plugin_name,
			$this->plugin_name . '-messages',
			array(
				'description' 	=> '',
				'fields' 		=> array(
					array(
						'text' => array(
							'class' 		=> '',
							'description' 	=> '',
							'id' 			=> 'column-name',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[column-name]',
							'placeholder' 	=> 'Column Name',
							'type' 			=> 'text',
							'value' 		=> ''
						),
					),
					array(
						'text' => array(
							'class' 		=> '',
							'description' 	=> '',
							'id' 			=> 'column-abbreviation',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[column-abbreviation]',
							'placeholder' 	=> 'Column abbreviation',
							'type' 			=> 'text',
							'value' 		=> ''
						),
					),
					array(
						'text' => array(
							'class' 		=> '',
							'description' 	=> '',
							'id' 			=> 'column-id',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[column-id]',
							'placeholder' 	=> 'Column ID',
							'type' 			=> 'text',
							'value' 		=> ''
						),
					),
					array(
						'number' => array(
							'class' 		=> '',
							'description' 	=> '',
							'id' 			=> 'column-width',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[column-width]',
							'placeholder' 	=> 'Column Width',
							'type' 			=> 'number',
							'value' 		=> ''
						),
					),
					array(
						'select' => array(
							'aria'			=> '',
							'blank'			=> '< select column type >',
							'class' 		=> '',
							'context' 		=> '',
							'description' 	=> '',
							'id' 			=> 'column-type',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[column-type]',
							'selections' 	=> array(
								array('label'=>'Text Column','value'=>'text-column'),
								array('label'=>'Title Column','value'=>'title-column'),
								array('label'=>'Progress Column','value'=>'progress-column'),
								array('label'=>'Image Column','value'=>'image-column')
							),
							'value' 		=> ''
						),
					),
				),
				'id' 			=> 'map-columns',
				'label-add' 	=> 'Add Column',
				'label-edit' 	=> 'Edit Column',
				'label-header' 	=> 'Column',
				'label-remove' 	=> 'Remove Column',
				'title-field' 	=> 'column-name'
			)
		);
	} // register_fields()
	
	
	
	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {
		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->plugin_name . '-messages',
			apply_filters( $this->plugin_name . 'section-title-messages', esc_html__( 'Messages', 'como-map' ) ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);
	} // register_sections()
	/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function register_settings() {
		// register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);
	} // register_settings()
	private function sanitizer( $type, $data ) {
		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }
		$return 	= '';
		$sanitizer 	= new Como_Map_Sanitize();
		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );
		$return = $sanitizer->clean();
		unset( $sanitizer );
		return $return;
	} // sanitizer()
	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function section_messages( $params ) {
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-section-messages.php' );
	} // section_messages()
	/**
	 * Sets the class variable $options
	 */
	private function set_options() {
		$this->options = get_option( $this->plugin_name . '-options' );
	} // set_options()
	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {
		//wp_die( print_r( $input ) );
		$valid 		= array();
		$options 	= $this->get_options_list();
		//foreach ($_POST as $k=>$v) { echo $k .' : '. $v .'<br>'; }
		
		foreach ( $options as $option ) {
			$name = $option[0];
			$type = $option[1];
			
			if ( 'repeater' === $type && is_array( $option[2] ) ) {
				$clean = array();
				foreach ( $option[2] as $field ) {
					foreach ( $input[$field[0]] as $data ) {
						if ( empty( $data ) ) { continue; }
						$clean[$field[0]][] = $this->sanitizer( $field[1], $data );
					} // foreach
				} // foreach
				$count = Como_Map_get_max( $clean );
				
				for ( $i = 0; $i < $count; $i++ ) {
					foreach ( $clean as $field_name => $field ) {
						
						//echo $field_name .' : '. implode(' - ',$field) .'<br>'; 
						
						if (isset($field[$i])) {
							$valid[$option[0]][$i][$field_name] = $field[$i];
						}
					} // foreach $clean
				} // for
			} else {
				$valid[$option[0]] = $this->sanitizer( $type, $input[$name] );
			}
			/*if ( ! isset( $input[$option[0]] ) ) { continue; }
			$sanitizer = new Como_Map_Sanitize();
			$sanitizer->set_data( $input[$option[0]] );
			$sanitizer->set_type( $option[1] );
			$valid[$option[0]] = $sanitizer->clean();
			if ( $valid[$option[0]] != $input[$option[0]] ) {
				add_settings_error( $option[0], $option[0] . '_error', esc_html__( $option[0] . ' error.', 'como-map' ), 'error' );
			}
			unset( $sanitizer );*/
		}
		return $valid;
	} // validate_options()
} // class