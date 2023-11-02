<script src="../../../../Scripts/swfobject_modified.js"></script>
<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="50" height="50">
  <param name="movie" value="../../../themes/spruce/Map Pin Icon">
  <param name="quality" value="high">
  <param name="wmode" value="opaque">
  <param name="swfversion" value="6.0.65.0">
  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
  <param name="expressinstall" value="../../../../Scripts/expressInstall.swf">
  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
  <!--[if !IE]>-->
  <object type="application/x-shockwave-flash" data="../../../themes/spruce/Map Pin Icon" width="50" height="50">
    <!--<![endif]-->
    <param name="quality" value="high">
    <param name="wmode" value="opaque">
    <param name="swfversion" value="6.0.65.0">
    <param name="expressinstall" value="../../../../Scripts/expressInstall.swf">
    <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
    <div>
      <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
    </div>
    <!--[if !IE]>-->
  </object>
  <!--<![endif]-->
</object>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>
<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Como_Map
 * @subpackage 	Como_Map/admin
 */
/**
 * The metabox-specific functionality of the plugin.
 *
 * @package 	Como_Map
 * @subpackage 	Como_Map/admin
 * @author 		Slushman <chris@slushman.com>
 */
class Como_Map_Admin_Metaboxes {
	/**
	 * The post meta data
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$meta    			The post meta data.
	 */
	private $meta;
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
		$this->set_meta();
	}
	/**
	 * Registers metaboxes with WordPress
	 *
	 * @since 	1.0.0
	 * @access 	public
	 */
	public function add_metaboxes() {
		// add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
		add_meta_box(
			'Como_Map_map_info',
			apply_filters( $this->plugin_name . '-metabox-title-map-info', esc_html__( 'Map Details', 'como-map' ) ),
			array( $this, 'metabox' ),
			'map',
			'normal',
			'default',
			array(
				'file' => 'como-map-info'
			)
		);
	} // add_metaboxes()
	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 *
	 * @since 		1.0.0
	 * @access 		public
	 * @return 		int 		The value of $nonce_check
	 */
	private function check_nonces( $posted ) {
		$nonces 		= array();
		$nonce_check 	= 0;
		$nonces[] 		= 'como_map_info';
		foreach ( $nonces as $nonce ) {
			if ( ! isset( $posted[$nonce] ) ) { $nonce_check++; }
			if ( isset( $posted[$nonce] ) && ! wp_verify_nonce( $posted[$nonce], $this->plugin_name ) ) { $nonce_check++; }
		}
		return $nonce_check;
	} // check_nonces()
	/**
	 * Returns an array of the all the metabox fields and their respective types
	 *
	 * @since 		1.0.0
	 * @access 		public
	 * @return 		array 		Metabox fields and types
	 */
	private function get_metabox_fields() {
		$fields = array();
		$fields = getCustomFields($fields);
		return $fields;
	} // get_metabox_fields()
	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @return 	void
	 */
	public function metabox( $post, $params ) {
		if ( ! is_admin() ) { return; }
		if ( 'map' !== $post->post_type ) { return; }
		if ( ! empty( $params['args']['classes'] ) ) {
			$classes = 'repeater ' . $params['args']['classes'];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-metabox-' . $params['args']['file'] . '.php' );
	} // metabox()
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
	 * Saves button order when buttons are sorted.
	 */
	/*public function save_files_order() {
		check_ajax_referer( 'como-map-file-order-nonce', 'fileordernonce' );
		$order 						= $this->meta['file-order'];
		$new_order 					= implode( ',', $_POST['file-order'] );
		$this->meta['file-order'] 	= $new_order;
		$update 					= update_post_meta( 'file-order', $new_order );
		esc_html_e( 'File order saved.', 'como-map' );
		die();
	}*/ // save_files_order()
	/**
	 * Sets the class variable $options
	 */
	public function set_meta() {
		global $post;
		if ( empty( $post ) ) { return; }
		if ( 'map' != $post->post_type ) { return; }
		//wp_die( '<pre>' . print_r( $post->ID ) . '</pre>' );
		$this->meta = get_post_custom( $post->ID );
	} // set_meta()
	/**
	 * Saves metabox data
	 *
	 * Repeater section works like this:
	 *  	Loops through meta fields
	 *  		Loops through submitted data
	 *  		Sanitizes each field into $clean array
	 *   	Gets max of $clean to use in FOR loop
	 *   	FOR loops through $clean, adding each value to $new_value as an array
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @param 	int 		$post_id 		The post ID
	 * @param 	object 		$object 		The post object
	 * @return 	void
	 */
	public function validate_meta( $post_id, $object ) {
		//wp_die( '<pre>' . print_r( $_POST ) . '</pre>' );
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return $post_id; }
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		if ( 'map' !== $object->post_type ) { return $post_id; }
		$nonce_check = $this->check_nonces( $_POST );
		if ( 0 < $nonce_check ) { return $post_id; }
		$metas = $this->get_metabox_fields();
		
		//foreach ($_POST as $k=>$v) { echo $k .' = '. $v .'<br>'; }
		
		foreach ( $metas as $meta ) {
			$name = $meta[0];
			$type = $meta[1];
			
			//echo $name .' : '. $type .' : '. $_POST[$name] .'<br>';
			if (isset($_POST[$name])) {
			
			if ( 'repeater' === $type && is_array( $meta[2] ) ) {
				$clean = array();
				foreach ( $meta[2] as $field ) {
					foreach ( $_POST[$field[0]] as $data ) {
						if ( empty( $data ) ) { continue; }
						$clean[$field[0]][] = $this->sanitizer( $field[1], $data );
					} // foreach
				} // foreach
				$count 		= Como_Map_get_max( $clean );
				$new_value 	= array();
				for ( $i = 0; $i < $count; $i++ ) {
					foreach ( $clean as $field_name => $field ) {
						$new_value[$i][$field_name] = $field[$i];
					} // foreach $clean
				} // for
			} elseif ($type === 'image') {
			
				$new_value = $_POST[$name];
			
			} else {
				
				$new_value = $this->sanitizer( $type, $_POST[$name] );
			
			}
			//echo '<p>'. $post_id .' - '. $name .' - '. $new_value .'</p>';	
			update_post_meta( $post_id, $name, $new_value );
			}
		} // foreach
	} // validate_meta()
} // class
