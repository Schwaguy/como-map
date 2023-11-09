<?php
/*
Plugin Name: Como Map
Plugin URI: http://www.comocreative.com/
Version: 1.2.4
Author: Como Creative LLC
Description: Enable custom Google Map embedding - Shortcode: [comomap maps=# OF MAPS maptype=STATIC/DYNAMIC mapid=MAP_ELEMENT_ID width=WIDTH height=HEIGHT labelclass=LABEL_CLASS label=LABEL address=STREET_ADDRESS lat=Latitude long=LONGITUDE centerlat=CENTER_LATITUDE centerlong=CENTER_LONGITUDE googlelink=GOOGLE_LINK binglink=GOOGLE_LINK content=INFO_BOX_CONTENT phone=PHONE countrycode=COUNTRY_CODE zoom=ZOOM style=1-10 icon=icon animate=TRUE/FALSE markercolor=COLOR showinfo=FALSE showon=click/hover]
*/
defined('ABSPATH') or die('No Hackers!');
/* Include plugin updater. */
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/updater.php' );
// Add Map Admin Stylesheet 
function map_admin_styles(){
    wp_enqueue_style('comomap_admin_style', trailingslashit(plugin_dir_url( __FILE__ )) .'css/admin.css');
}
add_action('admin_print_styles', 'map_admin_styles');
// Get Map Styles
if (!function_exists('comomap_get_styles')) {
	function comomap_get_styles() {
		$mapStyles[] = array('value'=>'default','text'=>'Default');
		
		// Get Pre-defined styles
		$templateDir = WP_PLUGIN_DIR . '/como-map/styles/'; 
		if ($handle = opendir($templateDir)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != ".." && $entry != "default.php") {
					$mapStyles[] = array('value'=>basename($entry, '.php'),'text'=>basename($entry, '.php'));
				}
			}
			closedir($handle);
		}
		
		// Look for custom styles in theme
		$templateDir = (is_child_theme() ? get_stylesheet_directory() : get_template_directory() ) . '/como-map/';
		if (file_exists($templateDir)) {
			$handle = opendir($templateDir);
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$mapStyles[] = array('value'=>basename($entry, '.php'),'text'=>basename($entry, '.php'));
				}
			}
			closedir($handle);
		}
		return $mapStyles;
	}
}
// Print String into Variable Function
if (!function_exists('insertStringText')) {
	function insertStringText($text,$txtdomain) {
		return __($text,$txtdomain);
	}
}

// Format Phone Number for Phone Link
if (!function_exists('formatPhoneLink')) {
	function formatPhoneLink($phoneNum,$ccode=1) {
		$phoneNum = $phoneNum;
		$phoneNum = preg_replace("/[^0-9]/", "", $phoneNum); 
		$phoneNum = '+'. $ccode . $phoneNum;
		return $phoneNum;
	}
}

// Como Map Settings Page
function comomap_register_settings() {
	
	// Map Logo
	add_option( 'comomap_logo', '');
	register_setting( 'comomap_options_group', 'comomap_logo', 'comomap_callback' );
	
	// Map Pin Icon
	add_option( 'comomap_icon', '');
	register_setting( 'comomap_options_group', 'comomap_icon', 'comomap_callback' );
	
	// Map Pin Icon Animation
	add_option( 'comomap_animate', '');
	register_setting( 'comomap_options_group', 'comomap_animate', 'comomap_callback' );
	
	// Google Maps Script async defer
	add_option( 'comomap_async_defer', '');
	register_setting( 'comomap_options_group', 'comomap_async_defer', 'comomap_callback' );
	
	// Map Label Color
	add_option( 'comomap_label_color', '');
	register_setting( 'comomap_options_group', 'comomap_label_color', 'comomap_callback' );
	
	// Google Maps API Key
	add_option( 'comomap_googlemaps_api_key', '');
	register_setting( 'comomap_options_group', 'comomap_googlemaps_api_key', 'comomap_callback' );
	
	// Map Style Selction
	add_option( 'comomap_mapstyle', '');
	register_setting( 'comomap_options_group', 'comomap_mapstyle', 'comomap_callback' );
	
	// Bing Maps API Key
	add_option( 'comomap_bingmaps_api_key', '');
	register_setting( 'comomap_options_group', 'comomap_bingmaps_api_key', 'comomap_callback' );
	
	// Google Maps Dynamic Styles
	add_option( 'comomap_googlemaps_styles_dynamic', '');
	register_setting( 'comomap_options_group', 'comomap_googlemaps_styles_dynamic', 'comomap_callback' );
	
	// Map Label
	add_option( 'comomap_label', '');
	register_setting( 'comomap_options_group', 'comomap_label', 'comomap_callback' );
	
	// Map Label Class
	add_option( 'comomap_label_class', '');
	register_setting( 'comomap_options_group', 'comomap_label_class', 'comomap_callback' );
	
	// Show Map Popup On
	add_option( 'comomap_showon', '');
	register_setting( 'comomap_options_group', 'comomap_showon', 'comomap_callback' );
	
	// AutoCenter
	add_option( 'comomap_autocenter', '');
	register_setting( 'comomap_options_group', 'comomap_autocenter', 'comomap_callback' );
	
	// Map Address
	add_option( 'comomap_address', '');
	register_setting( 'comomap_options_group', 'comomap_address', 'comomap_callback' );
	
	// Map Latitude
	add_option( 'comomap_latitude', '');
	register_setting( 'comomap_options_group', 'comomap_latitude', 'comomap_callback' );
	
	// Map Longitude
	add_option( 'comomap_longitude', '');
	register_setting( 'comomap_options_group', 'comomap_longitude', 'comomap_callback' );
	
	// Google Map Link
	add_option( 'comomap_google_link', '');
	register_setting( 'comomap_options_group', 'comomap_google_link', 'comomap_callback' );
	
	// Bing Map Link
	add_option( 'comomap_bing_link', '');
	register_setting( 'comomap_options_group', 'comomap_bing_link', 'comomap_callback' );
	
	
	// Google Maps Dynamic INfo Window HTML
	//add_option( 'comomap_googlemaps_infowindow_dynamic', '');
	//register_setting( 'comomap_options_group', 'comomap_googlemaps_infowindow_dynamic', 'comomap_callback' );
	
	// Google Maps Static Styles
	add_option( 'comomap_googlemaps_styles_static', '');
	register_setting( 'comomap_options_group', 'comomap_googlemaps_styles_static', 'comomap_callback' );
	
	// Bing Maps Dynamic Styles
	add_option( 'comomap_bingmaps_styles_dynamic', '');
	register_setting( 'comomap_options_group', 'comomap_bingmaps_styles_dynamic', 'comomap_callback' );
}
add_action( 'admin_init', 'comomap_register_settings' );
// Register Map Options Page
function comomap_register_options_page() {
  add_options_page('Map Settings', 'Map Settings', 'manage_options', 'como-map', 'comomap_options_page');
}
add_action('admin_menu', 'comomap_register_options_page');
// Populate Map Options Page
function comomap_options_page() {
	
	// Registers and enqueues the required javascript.
	wp_enqueue_media();
	wp_register_script('comomap-image-upload', plugin_dir_url( __FILE__ ) . 'js/comomap-image-uploader.js', array('jquery'));
	wp_localize_script('comomap-image-upload', 'meta_image',
		array(
			'title' => 'Choose or Upload an Image',
			'button' => 'Use this image',
		)
	);
	wp_enqueue_script('comomap-image-upload');
	?>
	<div>
		<?php //screen_icon(); ?>
		<h2>Map Options</h2>
		<form method="post" action="options.php">
			<?php 
				settings_fields( 'comomap_options_group' ); 
	
				// Map Logo
				$comomapLogo = get_option('comomap_logo');
				$logo = ((!empty($comomapLogo)) ? $comomapLogo : ''); 
				if ($logo) {
					$logo_src = wp_get_attachment_image_src($logo, 'full');
					$have_logo_img = is_array($logo_src);
					$logoUploadClass = 'hide';
					$logoRemoveClass = ''; 
				} else {
					$have_logo_img = false;
					$logoUploadClass = '';
					$logoRemoveClass = 'hide';
				}
			
				// map Pin Icon
				$comomapIcon = get_option('comomap_icon');
				$icon = ((!empty($comomapIcon)) ? $comomapIcon : ''); 
				if ($icon) {
					$img_src = wp_get_attachment_image_src($icon, 'full');
					$have_icon_img = is_array($img_src);
					$uploadClass = 'hide';
					$removeClass = ''; 
				} else {
					$have_icon_img = false;
					$uploadClass = '';
					$removeClass = 'hide';
				}
	
				// Animate Icon
				$animateIcon = get_option('comomap_animate');
				$animateIcon = ((empty($animateIcon)) ? 'TRUE' : $animateIcon);	
	
				// Show Label On
				$showOn = get_option('comomap_showon');
				$showOn = ((empty($showOn)) ? 'click' : $showOn);
	
				// Auto Center
				$autoCenter = get_option('comomap_autocenter');
				$autoCenter = ((empty($autoCenter)) ? 'default' : $autoCenter);
	
				// Async Defer Google Map Scrips
				$asyncDefer = get_option('comomap_async_defer');
				$asyncDefer = ((empty($asyncDefer)) ? 'click' : $asyncDefer);
	
				// Map Style Select
				$selectedStyle = (get_option('comomap_mapstyle') ? get_option('comomap_mapstyle') : '');
				$mapStyles = comomap_get_styles();
				$styleOptions = ''; 
				if (is_array($mapStyles)) {
					$styleCount = count($mapStyles);
					for ($s=0;$s<$styleCount;$s++) {
						$styleOptions .= '<option value="'. $mapStyles[$s]['value'] .'"'. (($selectedStyle == $mapStyles[$s]['value']) ? ' selected="selected"' : '' ) .'>'. $mapStyles[$s]['text'] .'</option>'; 
					}
					$styleOptions .= '<option value="custom"'. (($selectedStyle == 'custom') ? ' selected="selected"' : '' ) .'>Add Custom Style</option>';
				}
				$customClass = (($selectedStyle == 'custom') ? '' : 'hide');
			?>
			<p>Set your Map Global Options here.</p>
			<table style="width: 90%" id="map-config">
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_logo">Map Logo</label></p></th>
					<td style="width: 80%">
						<input class="url-file" id="comomap_logo" name="comomap_logo" type="hidden" value="<?=$logo?>" />
						<p><a href="#" class="<?=$logoUploadClass?> comomap-upload-image"><?php esc_html_e('Upload Image', 'comomap' ); ?></a>
						<a href="#" class="<?=$logoRemoveClass?> comomap-remove-image"><?php esc_html_e('Remove Image', 'comomap' ); ?></a></p>
						<div class="img-container img-preview">
							<?php if ( $logo ) : ?>
								<img src="<?=$logo_src[0]?>" alt="" style="max-width:100%;" />
							<?php endif; ?>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_icon">Map Pin Icon</label></p></th>
					<td style="width: 80%">
						<input class="url-file" id="comomap_icon" name="comomap_icon" type="hidden" value="<?=$icon?>" />
						<p><a href="#" class="<?=$uploadClass?> comomap-upload-image"><?php esc_html_e('Upload Image', 'comomap' ); ?></a>
						<a href="#" class="<?=$removeClass?> comomap-remove-image"><?php esc_html_e('Remove Image', 'comomap' ); ?></a></p>
						<div class="img-container img-preview">
							<?php if ( $icon ) : ?>
								<img src="<?=$img_src[0]?>" alt="" style="max-width:100%;" />
							<?php endif; ?>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_animate">Animate Icon</label></p></th>
					<td style="width: 80%">
						<p>
							<input type="radio" id="comomap_animate" name="comomap_animate" value="TRUE" <?=(($animateIcon == 'TRUE') ? 'checked="checked"' : '')?>> True &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
							<input type="radio" id="comomap_animate" name="comomap_animate" value="FALSE" <?=(($animateIcon == 'FALSE') ? 'checked="checked"' : '')?>> False
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_async_defer">Include "async defer" <br>in Google Maps Script Call</label></p></th>
					<td style="width: 80%">
						<p>
							<input type="radio" id="comomap_async_defer" name="comomap_async_defer" value="TRUE" <?=(($asyncDefer == 'TRUE') ? 'checked="checked"' : '')?>> True &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
							<input type="radio" id="comomap_async_defer" name="comomap_async_defer" value="FALSE" <?=(($asyncDefer == 'FALSE') ? 'checked="checked"' : '')?>> False
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_showon">Show Map PopUp On</label></p></th>
					<td style="width: 80%">
						<p>
							<input type="radio" id="comomap_showon" name="comomap_showon" value="click" <?=(($showOn == 'click') ? 'checked="checked"' : '')?>> Click &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
							<input type="radio" id="comomap_showon" name="comomap_showon" value="mouseover" <?=(($showOn == 'mouseover') ? 'checked="checked"' : '')?>> Hover
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_showon">Auto Center Pin in Click</label></p></th>
					<td style="width: 80%">
						<p>
							<input type="radio" id="comomap_autocenter" name="comomap_autocenter" value="default" <?=(($autoCenter == 'default') ? 'checked="checked"' : '')?>> Default (Enable Autocenter) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
							<input type="radio" id="comomap_autocenter" name="comomap_autocenter" value="disable" <?=(($autoCenter == 'disable') ? 'checked="checked"' : '')?>> Disable
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_mapstyle">Map Label Class</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_label_class" name="comomap_label_class" value="<?=get_option('comomap_label_class')?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_mapstyle">Map Style</label></p></th>
					<td style="width: 80%"><p><select id="comomap_mapstyle" name="comomap_mapstyle" class="widefat"><?=$styleOptions?></select></p></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_label">Map Label</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_label" name="comomap_label" value="<?=get_option('comomap_label')?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_address">Map Address</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_address" name="comomap_address" value="<?=get_option('comomap_address')?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_latitude">Map Latitude</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_latitude" name="comomap_latitude" value="<?=get_option('comomap_latitude')?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_longitude">Map Longitude</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_longitude" name="comomap_longitude" value="<?=get_option('comomap_longitude')?>" class="widefat" /></p></td>
				</tr>
				
				<tr><td colspan="2"><h2>Google Map Options</h2></td></tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_googlemaps_api_key">Google Maps API Key</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_googlemaps_api_key" name="comomap_googlemaps_api_key" value="<?=get_option('comomap_googlemaps_api_key')?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_google_link">Google Map Link</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_google_link" name="comomap_google_link" value="<?=get_option('comomap_google_link')?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top" class="custom-map-styler google-static <?=$customClass?>">
					<th scope="row"><p><label for="comomap_googlemaps_styles_dynamic">Google Maps Dynamic Styles</label></p></th>
					<td><p><textarea id="comomap_googlemaps_styles_dynamic" name="comomap_googlemaps_styles_dynamic" class="widefat" style="height: 300px"><?=get_option('comomap_googlemaps_styles_dynamic')?></textarea></p></td>
				</tr>
				<tr valign="top" class="custom-map-styler google-static <?=$customClass?>">
					<th scope="row"><p><label for="comomap_googlemaps_styles_static">Google Maps Static Styles</label></p></th>
					<td><p><textarea id="comomap_googlemaps_styles_static" name="comomap_googlemaps_styles_static" class="widefat" style="height: 300px"><?=get_option('comomap_googlemaps_styles_static')?></textarea></p></td>
				</tr>
				
				<tr><td colspan="2"><h2>Bing Map Options</h2></td></tr>
				<tr valign="top">
					<th scope="row"><p><label for="comomap_bingmaps_api_key">Bing  Maps API Key</label></p></th>
					<td><p><input type="text" id="comomap_bingmaps_api_key" name="comomap_bingmaps_api_key" value="<?php echo get_option('comomap_bingmaps_api_key'); ?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 20%"><p><label for="comomap_bing_link">Bing Map Link</label></p></th>
					<td style="width: 80%"><p><input type="text" id="comomap_bing_link" name="comomap_bing_link" value="<?=get_option('comomap_bing_link')?>" class="widefat" /></p></td>
				</tr>
				<tr valign="top" class="custom-map-styler bing-dynamic <?=$customClass?>">
					<th scope="row"><p><label for="comomap_bingmaps_styles_dynamic">Bing Maps Dynamic Styles</label></p></th>
					<td><p><textarea id="comomap_bingmaps_styles_dynamic" name="comomap_bingmaps_styles_dynamic" class="widefat" style="height: 300px"><?=get_option('comomap_bingmaps_styles_dynamic')?></textarea></p></td>
				</tr>
				
				<!--<tr valign="top" class="custom-map-styler">
					<th scope="row"><p><label for="comomap_googlemaps_infowindow_dynamic">Google Maps Dynamic Info Window HTML</label><br><em>(Only used for Google Dynamic Maps)</em></p></th>
					<td><p><textarea id="comomap_googlemaps_infowindow_dynamic" name="comomap_googlemaps_infowindow_dynamic" class="widefat" style="height: 100px"><?=get_option('comomap_googlemaps_infowindow_dynamic')?></textarea></p></td>
				</tr>-->
			</table>
			<?php  submit_button(); ?>
		</form>
	</div>
	<script>
		jQuery('#comomap_mapstyle').on('change', function($) {
		  	var selected = this.value;
			if (selected === 'custom') {
				jQuery('#map-config .custom-map-styler').removeClass('hide');
			} else {
				jQuery('#map-config .custom-map-styler').addClass('hide');
			}
		});
	</script>
	<?php
}
// Custom Image Sizes
add_action( 'after_setup_theme', 'comomap_img_sizes' );
function comomap_img_sizes() {
	add_image_size( 'map-logo-image', 400, 150, false ); 
	add_image_size( 'map-icon-image', 38, 38, false ); 
}
/* ##################### Shortcode to place Google Map ##################### */
// Usage: [comomap maps=# OF MAPS maptype=STATIC/DYNAMIC mapid=MAP_ELEMENT_ID width=WIDTH height=HEIGHT labelclass=LABEL_CLASS label=LABEL address=STREET_ADDRESS lat=Latitude long=LONGITUDE centerlat=CENTER_LATITUDE centerlong=CENTER_LONGITUDE googlelink=GOOGLE_LINK binglink=GOOGLE_LINK content=INFO_BOX_CONTENT phone=PHONE countrycode=COUNTRY_CODE zoom=ZOOM style=1-10 icon=icon animate=TRUE/FALSE markercolor=COLOR showinfo=FALSE showon=click/hover]
if (!isset($GLOBALS['footScript'])) { $GLOBALS['footScript'] = ''; }
class Como_Map_Shortcode {
	static $add_script;
	static $add_style;
	static function init() {
		add_shortcode('comomap', array(__CLASS__, 'handle_map_shortcode'));
		add_action('init', array(__CLASS__, 'register_map_script'));
		add_action('wp_footer', array(__CLASS__, 'print_map_script'));
	}
	static function handle_map_shortcode($atts) {
		if (!is_admin()) {
			self::$add_style = false;
			self::$add_script = false;
			unset($mapInfo);
			
			// Shortcode Attributes
			$lat = (isset($atts['lat']) ? $atts['lat'] : (get_option('comomap_latitude') ? get_option('comomap_latitude') : ''));
			$long = (isset($atts['long']) ? $atts['long'] : (get_option('comomap_longitude') ? get_option('comomap_longitude') : ''));
			$mapvars = array(
				'markers' => (isset($atts['markers']) ? $atts['markers'] : 1),
				'maps' => (isset($atts['maps']) ? $atts['maps'] : 1),
				'mapid' => $atts['mapid'],
				'maptype' => (isset($atts['maptype']) ? $atts['maptype'] : 'dynamic'),
				'labelclass' => (isset($atts['labelclass']) ? $atts['labelclass'] : (get_option('comomap_label_class') ? get_option('comomap_label_class') : '')),
				'label' => (isset($atts['label']) ? $atts['label'] : (get_option('comomap_label') ? get_option('comomap_label') : '')),
				'address' => (isset($atts['address']) ? $atts['address'] : (get_option('comomap_address') ? get_option('comomap_address') : '')),
				'lat' => $lat,
				'long' => $long,
				'centerlat' => (isset($atts['centerlat']) ? $atts['centerlat'] : $lat),
				'centerlong' => (isset($atts['centerlong']) ? $atts['centerlong'] : $long),
				'googlelink' => (isset($atts['googlelink']) ? $atts['googlelink'] : (get_option('comomap_google_link') ? get_option('comomap_google_link') : '')),
				'binglink' => (isset($atts['binglink']) ? $atts['binglink'] : (get_option('comomap_bing_link') ? get_option('comomap_bing_link') : '')),
				'phone' => (isset($atts['phone']) ? $atts['phone'] : ''),
				'countrycode' => (isset($atts['countrycode']) ? $atts['countrycode'] : 1),
				'content' => (isset($atts['content']) ? $atts['content'] : ''),
				'zoom' => (isset($atts['zoom']) ? $atts['zoom'] : 15),
				'mapstyle' => (isset($atts['mapstyle']) ? $atts['mapstyle'] : (get_option('comomap_mapstyle') ? get_option('comomap_mapstyle') : 'default')),
				'icon' => (isset($atts['icon']) ? $atts['icon'] : (get_option('comomap_icon') ? get_option('comomap_icon') : '')),
				'animate' => (isset($atts['animate']) ? $atts['animate'] : (get_option('comomap_animate') ? get_option('comomap_animate') : 'TRUE')),
				'asyncDefer' => (isset($atts['asyncdefer']) ? $atts['asyncdefer'] : (get_option('comomap_async_defer') ? get_option('comomap_async_defer') : 'FALSE')),
				'showon' => (isset($atts['showon']) ? $atts['showon'] : (get_option('comomap_showon') ? get_option('comomap_showon') : 'click')),
				'autocenter' => (isset($atts['autocenter']) ? $atts['autocenter'] : (get_option('comomap_autocenter') ? get_option('comomap_autocenter') : 'default')),
				'logo' => (isset($atts['logo']) ? $atts['logo'] : (get_option('comomap_logo') ? get_option('comomap_logo') : '')),
				'width' => (isset($atts['width']) ? $atts['width'] : 400),
				'height' => (isset($atts['height']) ? $atts['height'] : 300),
				'markercolor' => (isset($atts['markercolor']) ? $atts['markercolor'] : 0xff0000),
				'mapHTML' => (isset($atts['mapHTML']) ? $atts['mapHTML'] : '<div class="comomap" id="comomap"></div>'),
				'showinfo' => (isset($atts['showinfo']) ? $atts['showinfo'] : FALSE)
			);
			$comomap = constructComoMap($mapvars);
		}
	}
	
	// Register & Print Scripts
	static function register_map_script() {
		//wp_register_script('como_map_script', $mapScripts, array('jquery'), '1.0', true);
	}
	static function print_map_script() {
		if ( ! self::$add_script )
			return;
		//wp_print_scripts('como_map_script');
	}
}
Como_Map_Shortcode::init();
/* -------------------------------- Como Map Construct ------------------------------ */
if (!function_exists('constructComoMap')) {
	function constructComoMap($mapvars) {
		
		// Google Map Settings
		$googleAPI = get_option('comomap_googlemaps_api_key');
		$googleStylesDynamic = get_option('comomap_googlemaps_styles_dynamic');
		$googleStylesStatic = get_option('comomap_googlemaps_styles_static');
		$googleInfoWindow = get_option('comomap_googlemaps_infowindow_dynamic');
			
		// Bing Map Settings
		$bingAPI = get_option('comomap_bingmaps_api_key');
		$bingStylesDynamic = get_option('comomap_bingmaps_styles_dynamic');
			
		// Map Settings
		$markers = (isset($mapvars['markers']) ? $mapvars['markers'] : 1);
		$maps = (isset($mapvars['maps']) ? $mapvars['maps'] : 1);
		$mapid = $mapvars['mapid'];
		$maptype = (isset($mapvars['maptype']) ? $mapvars['maptype'] : 'dynamic');
		$labelclass = (isset($mapvars['labelclass']) ? $mapvars['labelclass'] : '');
		$label = (isset($mapvars['label']) ? $mapvars['label'] : '');
		$address = (isset($mapvars['address']) ? $mapvars['address'] : '');
		$lat = $mapvars['lat'];
		$long = $mapvars['long'];
		$centerlat = (isset($mapvars['centerlat']) ? $mapvars['centerlat'] : (is_array($lat) ? $lat[0] : $lat));
		$centerlong = (isset($mapvars['centerlong']) ? $mapvars['centerlong'] : (is_array($long) ? $long[0] : $long));
		$googlelink = (isset($mapvars['googlelink']) ? $mapvars['googlelink'] : '');
		$binglink = (isset($mapvars['binglink']) ? $mapvars['binglink'] : '');
		$phone = (isset($mapvars['phone']) ? $mapvars['phone'] : '');
		$countrycode = (isset($mapvars['countrycode']) ? $mapvars['countrycode'] : 1);
		$content = (isset($mapvars['content']) ? $mapvars['content'] : '');
		$zoom = (isset($mapvars['zoom']) ? $mapvars['zoom'] : 15);
		$style = (($mapvars['mapstyle']) ? $mapvars['mapstyle'] : '');
		$showinfo = (($mapvars['showinfo']) ? $mapvars['showinfo'] : FALSE);
		
		if (!empty($style)) {
			$customDynamic = get_option('comomap_googlemaps_styles_dynamic');
			$customStatic = get_option('comomap_googlemaps_styles_static');
			$customBing = get_option('comomap_bingmaps_styles_dynamic');
			
			$templateDir = (is_child_theme() ? get_stylesheet_directory() : get_template_directory() ) . '/como-map/';
			if (file_exists($templateDir . $style .'.php')) {
				include($templateDir . $style .'.php');
			} elseif (file_exists(WP_PLUGIN_DIR . '/como-map/styles/' . $style .'.php')) {
				include((WP_PLUGIN_DIR . '/como-map/styles/' . $style .'.php'));
			} elseif (!empty($customDynamic) || !empty($customStatic) || !empty($customBing)) {
				$mapStyle['dynamic'] = $customDynamic;
				$mapStyle['static'] = $customDynamic;
				$mapStyle['bing'] = $customBing;
			} else {
				include((WP_PLUGIN_DIR . '/como-map/styles/default.php'));
			}
		}
		
		$icon = (isset($mapvars['icon']) ? $mapvars['icon'] : '');
		$animate = (isset($mapvars['animate']) ? $mapvars['animate'] : 'TRUE');
		$showon = (isset($mapvars['showon']) ? $mapvars['showon'] : 'click');
		$autoCenter = (isset($mapvars['autocenter']) ? $mapvars['autocenter'] : 'default');
		$asyncDefer = (isset($mapvars['asyncDefer']) ? $mapvars['asyncDefer'] : 'FALSE');
		$logo = (isset($mapvars['logo']) ? $mapvars['logo'] : '');
		$width = (isset($mapvars['width']) ? $mapvars['width'] : 400);
		$height = (isset($mapvars['height']) ? $mapvars['height'] : 300);
		$markercolor = (isset($mapvars['markercolor']) ? $mapvars['markercolor'] : 0xff0000);
		$mapHTML = (isset($mapvars['mapHTML']) ? $mapvars['mapHTML'] : '<div class="comomap" id="comomap"></div>');
		
		// Map Pin Icon
		if ($icon != '') {
			$icon_img_src = wp_get_attachment_image_src($icon, 'map-icon-image');
			$icon = $icon_img_src[0];
		} else {
			$icon = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'img/default-pin.png';
		}
		
		// Map Logo
		if ($logo != '') {
			$logo_img_src = wp_get_attachment_image_src($logo, 'map-logo-image');
			$logo = '<img src="'. $logo_img_src[0] .'" class="img-responsive img-fluid mt-1 mb-2 info-window-logo">';
		} else {
			$logo = '';
		}
		
		$mapScripts = ''; 
			
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		//$lang = 'ZH'; 
			
		if ($markers>1) {
			$label = (!is_array($label) ? explode('|', $label) : preg_replace('/\r|\n/', '', $label));
			$address = (!is_array($address) ? explode('|', $address) : preg_replace('/\r|\n/', '', $address));
			$lat = (!is_array($lat) ? explode('|', $lat) : preg_replace('/\r|\n/', '', $lat));
			$long = (!is_array($long) ? explode('|', $long) : preg_replace('/\r|\n/', '', $long));
			$googlelink = (!is_array($googlelink) ? explode('|', $googlelink) : preg_replace('/\r|\n/', '', $googlelink));
			$binglink = (!is_array($binglink) ? explode('|', $binglink) : preg_replace('/\r|\n/', '', $binglink));
			$phone = (!is_array($phone) ? explode('|', $phone) : preg_replace('/\r|\n/', '', $phone));
			$countrycode = (!is_array($countrycode) ? explode('|', $countrycode) : preg_replace('/\r|\n/', '', $countrycode));
			$content = (!is_array($content) ? explode('|', $content) : preg_replace('/\r|\n/', '', $content));
						
			$markerArray = array();
			$windowArray = array();
			if ((strtolower($lang) == 'zh') && !empty($bingAPI)) {
				// Use Bing Maps
				for ($m=0; ($m<$markers); $m++) {
					$markerArray[$m] = "pushpinInfos[$m] = {'lat':$lat[$m],'lng':$long[$m],'title':'','description':'<div class=\"infoWindow\"><div class=\"info-wrap\">$logo<p class=\"address\">". $address[$m] ."</p>". ((isset($phone[$m])) ? "<h5><a href=\"tel:". formatPhoneLink($phone[$m],$countrycode[$m]) ."\" class=\"phoneLink\">". $phone[$m] ."</a></h5>" : ""). "<h5><a class=\"dirMapLink\" target=\"_blank\" href=\"https://www.bing.com/maps/directions?cp=". $lat[$m] ."~". $long[$m] ."&amp;sty=r&amp;rtp=~pos.". $lat[$m] ."_". $long[$m] ."____&amp;FORM=MBEDLD\" rel=\"noopener noreferrer\">". insertStringText('Get Directions','como-map') ."</a></h5></div></div>', 'icon':'". $icon ."'}";
				}
				$mapScripts .= "<script type=\"text/javascript\" async defer>
					var map;
					var pinInfobox;
					function GetMap() {
						var pushpinInfos = [];
						". implode(';',$markerArray) ."
						var infoboxLayer = new Microsoft.Maps.EntityCollection();
						var pinLayer = new Microsoft.Maps.EntityCollection();
						var apiKey = '". $bingAPI ."';
						var map = new Microsoft.Maps.Map(document.getElementById('". $mapid ."'), { 
							credentials: apiKey, 
							center: new Microsoft.Maps.Location(". $centerlat .",". $centerlong ."), 
							showMapTypeSelector: false, 
							enableSearchLogo: false, 
							enableClickableLogo: false, 
							showMapTypeSelector: false, 
							showScalebar: false, 
							showDashboard: false, 
							disableUserInput: false, 
							showMapLabels: false, 
							allowHidingLabelsOfRoad: true, 
							customMapStyle: {". $mapStyle['bing'] ."}, 
							maxZoom: 19, 
							minZoom: 1 
						});
						pinInfobox = new Microsoft.Maps.Infobox(new Microsoft.Maps.Location(0, 0), { visible: false });
						pinInfobox.setMap(map);
						
						var locs = [];
						for (var i = 0 ; i < pushpinInfos.length; i++) {
							locs[i] = new Microsoft.Maps.Location(pushpinInfos[i].lat, pushpinInfos[i].lng);
							var pin = new Microsoft.Maps.Pushpin(locs[i], {icon: pushpinInfos[i].icon, width:'20px', height:'20px'});
							pin.metadata = {
								title: pushpinInfos[i].title,
								description: pushpinInfos[i].description
							};
							Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);
							map.entities.push(pin);
						}
						var bestview = Microsoft.Maps.LocationRect.fromLocations(locs);
						map.setView({ center: bestview.center, zoom: ". $zoom ." });
					}
					function pushpinClicked(e) {
						if (e.target.metadata) {
							pinInfobox.setOptions({
								location: e.target.getLocation(),
								title: e.target.metadata.title,
								description: e.target.metadata.description,
								visible: true
							});
						}
					}
				</script>
				<script type=\"text/javascript\" src=\"https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=". $bingAPI ."\" async defer></script>"; 
			} else {
				// Use Google Maps
				for ($m=0; ($m<$markers); $m++) {
					$markerArray[$m] = "['". ((isset($address[$m])) ? $address[$m] : '') ."', ". ((isset($lat[$m])) ? $lat[$m] : '') .", ". ((isset($long[$m])) ? $long[$m] : '') .", '". ((isset($googlelink[$m])) ? $googlelink[$m] : '') ."', '". ((isset($phone[$m])) ? $phone[$m] : '') ."', '". ((isset($label[$m])) ? $label[$m] : '') ."']";
					$windowArray[$m] = "['<div id=\"infoWindow\"><div class=\"info-wrap\"><div class=\"info-wrap\"><div class=\"row\"><div class=\"col col-12 col-xs-12 col-sm-12 col-md-8 col-logo\">". ((isset($logo)) ? $logo : '') ."</div></div><p class=\"address\">". ((isset($address[$m])) ? $address[$m] : '') ."</p><div class=\"content\">". ((isset($content[$m])) ? $content[$m] : '') ."</div>". ((isset($phone[$m])) ? "<h5><a href=\"tel:". formatPhoneLink($phone[$m], ((isset($countrycode[$m])) ? $countrycode[$m] : 1) ) ."\" class=\"phoneLink\">". $phone[$m] ."</a></h5>" : "") ."<h5>". ((isset($googlelink[$m])) ? "<a data-ajax=\"false\" target=\"_blank\" href=\"". $googlelink[$m] ."\">". insertStringText('Get Directions','como-map') ."</a>"  : '') ."</h5></div> </div></div>']";
				}
				$mapLabel = ((!empty($label)) ? "label: { text: markers[i][5], ". (($labelclass) ? "className: '". $labelclass ."'" : "") ." }" : '');
				$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."&callback=initMap\" ". (($asyncDefer == 'TRUE') ? 'async defer' : '') ."></script>";
				//$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."&callback=initMap\"></script>";
				$mapScripts .= "<script async defer> var marker; function initMap() { 
					var centerLatLng = {
						lat:". $centerlat .",
						lng:". $centerlong ."
					}; 
					var map;
					var bounds = new google.maps.LatLngBounds();
					var mapOptions = {
						center: centerLatLng, zoom: ". $zoom .", 
						styles: ". $mapStyle['dynamic'] .", 
						disableDefaultUI: true 
					};
					// Display a map on the page
					map = new google.maps.Map(document.getElementById('". $mapid ."'), mapOptions);
					// Multiple Markers
					var markers = [". implode(',',$markerArray) ."];
					// Info Window Content
					var infoWindowContent = [". implode(',',$windowArray) ."];
					// Display multiple markers on a map
					var infoWindow = new google.maps.InfoWindow(), marker, i;
					// Loop through our array of markers & place each one on the map  
					for( i = 0; i < markers.length; i++ ) {
						var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
						bounds.extend(position);
						marker = new google.maps.Marker({
							map: map, draggable: false, 
							icon: '". $icon ."', 
							". (($animate == 'TRUE') ? "animation: google.maps.Animation.DROP," : "") ." 
							position: position, 
							title: '". get_bloginfo('name') ."',
							". $mapLabel ."
						});
						// Allow each marker to have an info window    
						google.maps.event.addListener(marker, '". $showon ."', (function(marker, i) {
							return function() {
								infoWindow.setContent(infoWindowContent[i][0]);
								infoWindow.open(map, marker);
							}
						})(marker, i));
						". (($showon == 'mouseover') ? "google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
							return function() {
								setTimeout(function() {
									infoWindow.close(map, marker);
								}, 2000);
							}
						})(marker, i));" : "") ."
						". (($showinfo) ? 'infoWindow.open(map, marker);' : '') ."
						// Automatically center the map fitting all markers on the screen
						//map.fitBounds(bounds);
					}
				} 
				function toggleBounce() { 
					if (marker.getAnimation() !== null) { 
						marker.setAnimation(null); 
					} else { 
						marker.setAnimation(google.maps.Animation.BOUNCE); 
					} 
				} 
				</script>";
			}
		} else {
			// If Multiple Maps	
			if ($maps>1) {
				
				if ((strtolower($lang) == 'zh') && !empty($bingAPI)) {
					// Use Bing Maps
					$mapScripts .= "
					<script async defer>
						function GetMap() {
							mapNum=$maps; 
							mapids='$mapid'.split('|'); 
							lats='$lat'.split('|'); 
							longs='$long'.split('|'); 
							centerlats='$centerlat'.split('|'); 
							centerlongs='$centerlong'.split('|'); 
							links='$googlelink'.split('|'); 
							phones='$phone'.split('|'); 
							countrycodes='$countrycode'.split('|'); 
							labels='$label'.split('|');
							addresses='$address'.split('|'); 
							contents='$content'.split('|');
							mapZoom = ". ((strpos($zoom, '|')) ? "'$zoom'.split('|')" : $zoom) .";
							for(var i=0;i<". $maps .";i++){ 
								var mapid, lat, long, centerlat, centerlong, link, phone, countrycode, label, address, content, mapZoom; 
								mapid = mapids[i];  
								lat = lats[i]; 
								long = longs[i]; 
								centerlat = centerlats[i]; 
								centerlong = centerlongs[i]; 
								link = links[i]; 
								phone = phones[i]; 
								phone = ((phone) ? '<h5><a href=\"tel:+'+ countrycode + phone +'\" class=\"phoneLink\">'+ phone +'</a></h5><h5>' : '');
								countrycode = countrycodes[i]; 
								label = labels[i];
								address = addresses[i];
								address = ((address) ? '<p class=\"address\">'+ address +'</p>' : '');
								mpZoom = parseInt(". ((strpos($zoom, '|')) ? "mapZoom[i]" : $zoom) .");
								
								mapid = new Microsoft.Maps.Map('#'+mapid, { 
									center: new Microsoft.Maps.Location(centerlat,centerlong), 
									showMapTypeSelector: false, 
									enableSearchLogo: false, 
									enableClickableLogo: false, 
									showMapTypeSelector: false, 
									showScalebar: false, 
									showDashboard: false, 
									disableUserInput: false, 
									showMapLabels: false, 
									allowHidingLabelsOfRoad: true, 
									customMapStyle: {". $mapStyle['bing'] ."}, 
									maxZoom: 19, 
									minZoom: 6 
								}); 
								var center = mapid.getCenter(); 
								pinInfobox = new Microsoft.Maps.Infobox(new Microsoft.Maps.Location(0, 0), { visible: false });
								pinInfobox.setMap(mapid);
								var pin = new Microsoft.Maps.Pushpin(center, { 
									icon: '". $icon ."', 
									anchor: new Microsoft.Maps.Point(12, 39) 
								}); 
								pin.metadata = {
									title: '',
									description: '<div class=\"infoWindow\"><div class=\"info-wrap\">$logo'+ address + phone +'<h5><a class=\"dirMapLink\" target=\"_blank\" href=\"https://www.bing.com/maps/directions?cp='+ lat +'~'+ long +'&amp;sty=r&amp;rtp=~pos.'+ lat +'_'+ long +'____&amp;FORM=MBEDLD\" rel=\"noopener noreferrer\">". insertStringText('Get Directions','como-map') ."</a></h5></div></div>'
								};
								Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);
								mapid.entities.push(pin);
								mapid.setView({ center: center, zoom: '+ mpZoom +' });
							}
							function pushpinClicked(e) {
								if (e.target.metadata) {
									pinInfobox.setOptions({
										location: e.target.getLocation(),
										title: e.target.metadata.title,
										description: e.target.metadata.description,
										visible: true
									});
								}
							}
						}
					</script>
					<script type=\"text/javascript\" src=\"https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=". $bingAPI ."\" async defer></script>";
					
					/* ================= NOTE: Need to figure out multi-map info bos issue - only last instance working 4/12/2021 - JSC ================= */
					
				} else {
					// Use Google Maps	
					if ($maptype == 'static') {
						//echo 'STATIC'; 
						$mapScripts .= "<script async defer>
							var mapid, mapNum, lat, long, centerlat, centerlong, link, phone, countrycode, address, mapAddress, mapAddressEncoded, mapCenterLat, mapCenterLong, mapHTML, mapLat, mapLong, mapZoom, mpZoom;
							mapNum='$maps'; 
							mapid='$mapid'.split('|'); 
							lat='$lat'.split('|'); 
							long='$long'.split('|'); 
							centerlat='$centerlat'.split('|'); 
							centerlong='$centerlong'.split('|'); 
							link='$googlelink'.split('|'); 
							phone='$phone'.split('|'); 
							countrycode='$countrycode'.split('|'); 
							address='$address'.split('|'); 
							mapZoom = ". ((strpos($zoom, '|')) ? "'$zoom'.split('|')" : $zoom) .";
							for(var i=0;i<". $maps .";i++){ 
								mapAddress = address[i].replace('\'',''); 
								mapAddressEncoded = mapAddress.replace(' ','+'); 
								mapLat = parseFloat(lat[i].replace('\'','')); 
								mapLong = parseFloat(long[i].replace('\'','')); 
								mapCenterLat = parseFloat(centerlat[i].replace('\'','')); 
								mapCenterLong = parseFloat(centerlong[i].replace('\'','')); 
								mpZoom = ". ((strpos($zoom, '|')) ? "mapZoom[i]" : $zoom) .";
								mapHTML='<a class=\"showMap\" href=\"https://www.google.com/maps/place/'+ mapAddressEncoded +'/\" target=\"_blank\" data-mapid=\"mapid[i]\" data-lat=\"lat[i]\" data-long=\"long[i]\" data-centerlat=\"centerlat[i]\" data-centerlong=\"centerlong[i]\" data-link=\"link[i]\" data-phone=\"phone[i]\" data-counntycode=\"countrycode[i]\" data-address=\"address[i]\" data-zoom=\"mpZoom\" data-style=\"". str_replace("'","\'",$googleStylesStatic) ."\" data-icon=\"". $icon ."\" data-title=\"". get_bloginfo('name') ."\" data-logo=\"". $logo ."\"><img src=\"https://maps.googleapis.com/maps/api/staticmap?key=". $googleAPI ."&markers=size:mid%7Ccolor:0x". $markercolor ."%7C'+ mapAddressEncoded +'&  center='+ mapCenterLat +',-'+ mapCenterLong +'&zoom='+ mpZoom +'&format=png&maptype=roadmap&style=". $mapStyle['static'] ."&size=". $width ."x". $height ."\" alt=\"'+ mapAddress +'\" class=\"img-responsive img-fluid\"></a>'; jQuery('#'+ mapid[i]).html(mapHTML); 
							} 
						</script>";
					} else {
						//echo 'DYNAMIC';
						//$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."&callback=initMap\" async defer></script>";
						$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."\" ". (($asyncDefer == 'TRUE') ? 'async defer' : '') ."></script>";
						//$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."\"></script>";
						$mapScripts .= "<script async defer>
							jQuery(document).ready(function() { 
								function initMap() {};
								var mapids, lats, longs, centerlats, centerlongs, links, phones, countrycodes, addresses, contents, mapAddress, mapAddressEncoded, mapCenterLat, mapCenterLong, mapHTML, mapZoom; 
								var infowindows = {}; 
								var markers = {}; 
								var maps = {}; 
								mapNum = $maps; 
								mapids = '$mapid'.split('|'); 
								lats = '$lat'.split('|'); 
								longs = '$long'.split('|'); 
								centerlats = '$centerlat'.split('|'); 
								centerlongs = '$centerlong'.split('|'); 
								links = '$googlelink'.split('|'); 
								phones = '$phone'.split('|'); 
								countrycodes = '$countrycode'.split('|'); 
								labels = '$label'.split('|');
								addresses = '$address'.split('|'); 
								contents = '$content'.split('|'); 
								mapZoom = ". ((strpos($zoom, '|')) ? "'$zoom'.split('|')" : $zoom) .";
								for(var i=0;i<". $maps .";i++){ 
									function initMap() { 
										var mapid, lat, long, centerlat, centerlong, link, phone, countrycode, label, address, content; 
										mapid = mapids[i];  
										lat = lats[i]; 
										long = longs[i]; 
										centerlat = centerlats[i]; 
										centerlong = centerlongs[i]; 
										link = links[i]; 
										phone = phones[i]; 
										phone = ((phone) ? '<h5><a href=\"tel:+'+ countrycode + phone +'\" class=\"phoneLink\">'+ phone +'</a></h5><h5>' : '');
										countrycode = countrycodes[i]; 
										label = labels[i];
										address = addresses[i]; 
										address = ((address) ? '<p class=\"address\">'+ address +'</p>' : '');
										content = ((contents[i]) ? '<div class=\"info-content\">'+ contents[i] +'</div>' : ''); 
										var locLatLng = { 
											lat: parseFloat(lat), 
											lng: parseFloat(long) 
										};  
										var centerLatLng = { 
											lat: parseFloat(centerlat), 
											lng: parseFloat(centerlong) 
										}; 
										maps['map_'+ mapid] = new google.maps.Map(document.getElementById(mapid), { 
											center: centerLatLng, 
											zoom: parseInt(". ((strpos($zoom, '|')) ? "mapZoom[i]" : $zoom) ."), 
											styles: ". $mapStyle['dynamic'] .", 
											disableDefaultUI: true  
										}); 
										var contentString = '<div id=\"infoWindow_'+ mapid +'\"><div class=\"info-wrap\"><div class=\"row\"><div class=\"col col-12 col-xs-12 col-sm-12 col-md-8 col-logo\">". $logo ."</div></div>'+ address + content + phone +'<h5><a data-ajax=\"false\" target=\"_blank\" href=\"'+ link +'\">". insertStringText('Get Directions','como-map') ."</a></h5></div></div>';  
										infowindows['infowindow_'+ mapid] = new google.maps.InfoWindow({ 
											content: contentString,
											". (($autoCenter == 'disable') ? 'disableAutoPan:true' : '') ."
										}); 
										markers['marker_'+ mapid] = new google.maps.Marker({ 
											map: maps['map_'+ mapid], 
											draggable: false, 
											icon: '". $icon ."', 
											". (($animate == 'TRUE') ? "animation: google.maps.Animation.DROP," : "") ." 
											position: locLatLng, 
											title: '". get_bloginfo('name') ."',
											". (($label) ? "label: { text: label, ". (($labelclass) ? "className: '". $labelclass ."'" : "") ." }" : "") ."
										}); 
										markers['marker_'+ mapid].addListener('". $showon ."', function() { 
											infowindows['infowindow_'+ mapid].open(maps['map_'+ mapid], 
											markers['marker_'+ mapid]); 
										}); 
										". (($showon == 'mouseover') ? "markers['marker_'+ mapid].addListener('mouseout', function() { 
										setTimeout(function() { 
												infowindows['infowindow_'+ mapid].close(maps['map_'+ mapid], 
												markers['marker_'+ mapid]);
											 }, 2000);
										});" : "") ."
										". (($showinfo) ? "infowindows['infowindow_'+ mapid].open(maps['map_'+ mapid],markers['marker_'+ mapid]);" : '') ."
									} 
									initMap(); 
								} 
							}); 
						</script>";
						//$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."\" async defer></script>";
						//$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."&callback=initMap\" async defer></script>";
					}
				}
			} else {
				if ((strtolower($lang) == 'zh') && !empty($bingAPI)) {
					// Use Bing Maps
					$mapScripts .= "<script type=\"text/javascript\" async defer>
						var map; 
						function GetMap() { 
							map = new Microsoft.Maps.Map('#". $mapid ."', { 
								center: new Microsoft.Maps.Location(". $centerlat .",". $centerlong ."), 
								showMapTypeSelector: false, 
								enableSearchLogo: false, 
								enableClickableLogo: false, 
								showMapTypeSelector: false, 
								showScalebar: false, 
								showDashboard: false, 
								disableUserInput: false, 
								showMapLabels: false, 
								allowHidingLabelsOfRoad: true, 
								customMapStyle: {". $mapStyle['bing'] ."}, 
								maxZoom: 19, 
								minZoom: 6 
							}); 
							var center = map.getCenter(); 
							
							
							pinInfobox = new Microsoft.Maps.Infobox(new Microsoft.Maps.Location(0, 0), { visible: false });
							pinInfobox.setMap(map);
							
							var pin = new Microsoft.Maps.Pushpin(center, { 
								icon: '". $icon ."', 
								anchor: new Microsoft.Maps.Point(12, 39) 
							}); 
							pin.metadata = {
								title: '',
								description: '<div class=\"infoWindow\"><div class=\"info-wrap\">$logo<p class=\"address\">". $address ."</p><h5><a href=\"tel:". formatPhoneLink($phone,$countrycode) ."\" class=\"phoneLink\">". $phone ."</a></h5><h5><a class=\"dirMapLink\" target=\"_blank\" href=\"https://www.bing.com/maps/directions?cp=". $lat ."~". $long ."&amp;sty=r&amp;rtp=~pos.". $lat ."_". $long ."____&amp;FORM=MBEDLD\" rel=\"noopener noreferrer\">". insertStringText('Get Directions','como-map') ."</a></h5></div></div>'
							};
							Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);
							map.entities.push(pin);
							map.setView({ center: center, zoom: ". ($zoom) ." });
						}
						function pushpinClicked(e) {
							if (e.target.metadata) {
								pinInfobox.setOptions({
									location: e.target.getLocation(),
									title: e.target.metadata.title,
									description: e.target.metadata.description,
									visible: true
								});
							}
						}
						</script>
						<script type=\"text/javascript\" src=\"https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=". $bingAPI ."\" async defer></script>";
				} else {
					// Use Google Maps
					if ($maptype == 'static') {
						//echo 'STATIC';
						$mapScripts .= "<script async defer>var mapid, lat, long, centerlat, centerlong, link, phone, countrycode, address, mapAddress, mapAddressEncoded, mapCenterLat, mapCenterLong, mapHTML; mapAddress=''; mapNum=$maps; 
						mapid='$mapid'; 
						lat='$lat'; 
						long='$long'; 
						centerlat='$centerlat'; 
						centerlong='$centerlong'; 
						link='$googlelink'; 
						phone='$phone';
						countrycode='$countrycode';
						address='$address'; 
						mapAddress=address.replace('\'',''); mapAddressEncoded=mapAddress.replace(' ','+'); mapLat=parseFloat(lat.replace('\'','')); mapLong=parseFloat(long.replace('\'','')); mapCenterLat=parseFloat(centerlat.replace('\'','')); mapCenterLong=parseFloat(centerlong.replace('\'','')); mapHTML='<a href=\"https://www.google.com/maps/place/'+ mapAddressEncoded +'/\"><img src=\"https://maps.googleapis.com/maps/api/staticmap?key=AIzaSyBXtDCyTzd4Et2ZwtER8USoo2YkqtWusfc&markers=size:mid%7Ccolor:0x". $markercolor ."%7C'+ mapAddressEncoded +'&  center='+ mapCenterLat +',-'+ mapCenterLong +'&zoom=". $zoom ."&format=png&maptype=roadmap&style=". $mapStyle['static'] ."&size=". $width ."x". $height ."\" alt=\"'+ mapAddress +'\"></a>'; jQuery('#'+ mapid).html(mapHTML);</script>";
						//$mapDiv = '<div class="comomap" id="'. $mapid .'"></div>';
						//return($mapDiv);
					} else {
						$content = (($content) ? '<div class="info-content">'. $content .'</div>' : '');
						$mapLabel = (($label) ? "label: { text: '". $label ."', ". (($labelclass) ? "className: '". $labelclass ."'" : "") ." }" : '');
						$mapScripts .= "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleAPI ."&callback=initMap\" ". (($asyncDefer == 'TRUE') ? 'async defer' : '') ."></script><script async defer>
						// Single Dynamic Map
						function initMap() { 
							var locLatLng = { lat:". $lat .", lng:". $long ."}; 
							var centerLatLng = { lat:". $centerlat .", lng:". $centerlong ." }; 
							var map = new google.maps.Map(document.getElementById('". $mapid ."'), { 
								center: centerLatLng, 
								zoom: ". $zoom .", 
								styles: ". $mapStyle['dynamic'] .", 
								disableDefaultUI: true 
							}); 
							var contentString = '<div id=\"infoWindow\"><div class=\"info-wrap\"><div class=\"row\"><div class=\"col col-12 col-xs-12 col-sm-12 col-md-8 col-logo\">". $logo ."</div></div><p class=\"address\">". $address ."</p><div class=\"content\">". $content ."</div>". ((!empty($phone)) ? "<h5><a href=\"tel:". formatPhoneLink($phone,$countrycode) ."\" class=\"phoneLink\">". $phone ."</a></h5>" : "") ."<h5><a data-ajax=\"false\" target=\"_blank\" href=\"". $googlelink ."\">". insertStringText('Get Directions','como-map') ."</a></h5></div></div>';
							var infowindow = new google.maps.InfoWindow({ 
								content: contentString,
								". (($autoCenter == 'disable') ? 'disableAutoPan:true' : '') ."
							}); 
							var marker = new google.maps.Marker({ 
								map: map, draggable: false, 
								icon: '". $icon ."', 
								". (($animate == 'TRUE') ? "animation: google.maps.Animation.DROP," : "") ." 
								position: locLatLng, 
								title: '". get_bloginfo('name') ."',
								". $mapLabel ."
							}); 
							marker.addListener('click', toggleBounce); 
							marker.addListener('". $showon ."', function() { 
								infowindow.open(map, marker); 
							}); 
							". (($showon == 'mouseover') ? "marker.addListener('mouseout', function() { setTimeout(function() { infowindow.close(map, marker); }, 2000); });" : '') ."
							". (($showinfo) ? "infowindow.open(map, marker);" : '') ."
							function toggleBounce() { 
								function toggleBounce() { 
									if (marker.getAnimation() !== null) { 
										marker.setAnimation(null); 
									} else { 
										marker.setAnimation(google.maps.Animation.BOUNCE); 
									} 
								} 
							}
						}
						</script>";
						//$mapDiv = '<div class="comomap" id="'. $mapid .'"></div>';s
						//return($mapDiv);
					}
				}
			}
		}
		$GLOBALS['footScript'] .= $mapScripts;
	}
}
	
/* --------------------------------- Como Map Widget --------------------------------- */
// Register and load the widget
function comomap_load_widget() {
    register_widget( 'como_map_widget' );
}
add_action( 'widgets_init', 'comomap_load_widget' );
 
// Creating the widget 
class como_map_widget extends WP_Widget {
	function __construct() {
		parent::__construct('como_map_widget',	__('Map Widget', 'como-map'),array( 'description' => __( 'Displays a Map Widget', 'como-map' ), ) 
		);
	}
	
	// [comomap maps=# OF MAPS maptype=STATIC/DYNAMIC mapid=MAP_ELEMENT_ID scale=1/2 width=WIDTH height=HEIGHT labelclass=LABEL_CLASS label=LABEL address=STREET_ADDRESS lat=Latitude long=LONGITUDE centerlat=CENTER_LATITUDE centerlong=CENTER_LONGITUDE googlelink=GOOGLE_LINK binglink=BING_LINK phone=PHONE countrycode=COUNTRY_CODE zoom=ZOOM style=1-10 icon=icon animate=TRUE/FALSE markercolor=COLOR showinfo=FALSE showon=click/hover]
	
	// Creating widget front-end
	public function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
    	
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		$lat = (isset($instance['lat']) ? $instance['lat'] : (get_option('comomap_latitude') ? get_option('comomap_latitude') : ''));
		$long = (isset($instance['long']) ? $instance['long'] : (get_option('comomap_longitude') ? get_option('comomap_longitude') : ''));
		
		$mapvars = array(
			'markers' => (isset($instance['markers']) ? $instance['markers'] : 1),
			'maps' => (isset($instance['maps']) ? $instance['maps'] : 1),
			'mapid' => $instance['mapid'],
			'maptype' => (isset($instance['maptype']) ? $instance['maptype'] : 'dynamic'),
			'labelclass' => (isset($instance['labelclass']) ? $instance['labelclass'] : (get_option('comomap_label_class') ? get_option('comomap_label_class') : '')),
			'label' => (isset($instance['label']) ? $instance['label'] : (get_option('comomap_label') ? get_option('comomap_label') : '')),
			'address' => (isset($instance['address']) ? $instance['address'] : (get_option('comomap_address') ? get_option('comomap_address') : '')),
			'lat' => $lat,
			'long' => $long,
			'centerlat' => (isset($instance['centerlat']) ? $instance['centerlat'] : $lat),
			'centerlong' => (isset($instance['centerlong']) ? $instance['centerlong'] : $long),
			'googlelink' => (isset($instance['googlelink']) ? $instance['googlelink'] : (get_option('comomap_google_link') ? get_option('comomap_google_link') : '')),
			'binglink' => (isset($instance['binglink']) ? $instance['binglink'] : (get_option('comomap_bing_link') ? get_option('comomap_bing_link') : '')),
			'phone' => (isset($instance['phone']) ? $instance['phone'] : ''),
			'countrycode' => (isset($instance['countrycode']) ? $instance['countrycode'] : 1),
			'content' => (isset($instance['content']) ? $instance['content'] : ''),
			'zoom' => (isset($instance['zoom']) ? $instance['zoom'] : 15),
			'mapstyle' => (isset($instance['mapstyle']) ? $instance['mapstyle'] : (get_option('comomap_mapstyle') ? get_option('comomap_mapstyle') : 'default')),
			'icon' => (isset($instance['icon']) ? $instance['icon'] : (get_option('comomap_icon') ? get_option('comomap_icon') : '')),
			'animate' => (isset($instance['animate']) ? $instance['animate'] : (get_option('comomap_animate') ? get_option('comomap_animate') : 'TRUE')),
			'showon' => (isset($instance['showon']) ? $instance['showon'] : (get_option('comomap_showon') ? get_option('comomap_showon') : 'click')),
			'asyncDefer' => (isset($instance['asyncdefer']) ? $instance['asyncdefer'] : (get_option('comomap_async_defer') ? get_option('comomap_async_defer') : 'FALSE')),
			'logo' => (isset($instance['logo']) ? $instance['logo'] : (get_option('comomap_logo') ? get_option('comomap_logo') : '')),
			'width' => (isset($instance['width']) ? $instance['width'] : 400),
			'height' => (isset($instance['height']) ? $instance['height'] : 300),
			'markercolor' => (isset($instance['markercolor']) ? $instance['markercolor'] : 0xff0000),
			'mapHTML' => (isset($instance['mapHTML']) ? $instance['mapHTML'] : '<div class="comomap" id="comomap"></div>'),
			'showinfo' => (isset($instance['showinfo']) ? $instance['showinfo'] : FALSE)
		);
		$comomap = constructComoMap($mapvars);
		$mapHTML = $mapvars['mapHTML'];
		
		// Construct Widget
		$widget = ''; 
    	$widget .= (isset($before_widget) ? $before_widget : '');
    	if (!empty($title)) {
      		$widget .= $before_title . $title . $after_title;
		}
    	if (!empty($mapHTML)) {
      		$widget .= $mapHTML;
		}
    	$widget .= (isset($after_widget) ? $after_widget : '');
		echo (!empty($widget) ? $widget : '');
	}
	
	// Widget Backend 
	public function form( $instance ) {
		
		// Registers and enqueues the required javascript.
		wp_enqueue_media();
		wp_register_script('comomap-image-upload', plugin_dir_url( __FILE__ ) . 'js/comomap-image-uploader.js', array('jquery'));
		wp_localize_script('comomap-image-upload', 'meta_image',
			array(
				'title' => 'Choose or Upload an Image',
				'button' => 'Use this image',
			)
		);
		wp_enqueue_script('comomap-image-upload');
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
     	$title = $instance['title'];
		$maps = 1;
		$markers = $instance['markers'];
     	$maptype = $instance['maptype']; 
		$mapStyle = $instance['mapstyle'];
		$mapid = $instance['mapid'];
		$mapscale = $instance['mapscale'];
		$width = $instance['width'];
		$height = $instance['height'];
		$labelclass = ((isset($instance['labelclass'])) ? (is_array($instance['labelclass']) ? implode(PHP_EOL, $instance['labelclass']) : $instance['labelclass']) : '');
		$label = (is_array($instance['label']) ? implode(PHP_EOL, $instance['label']) : $instance['label']);
		$address = (is_array($instance['address']) ? implode(PHP_EOL, $instance['address']) : $instance['address']);
		$lat = (is_array($instance['lat']) ? implode(PHP_EOL, $instance['lat']) : $instance['lat']);
		$long = (is_array($instance['long']) ? implode(PHP_EOL, $instance['long']) : $instance['long']);
		$centerlat = $instance['centerlat'];
		$centerlong = $instance['centerlong'];
		$googlelink = (is_array($instance['googlelink']) ? implode(PHP_EOL, $instance['googlelink']) : $instance['googlelink']);
		$binglink = (is_array($instance['binglink']) ? implode(PHP_EOL, $instance['binglink']) : $instance['binglink']);
		$phone = (is_array($instance['phone']) ? implode(PHP_EOL, $instance['phone']) : $instance['phone']);
		$countrycode = (is_array($instance['countrycode']) ? implode(PHP_EOL, $instance['countrycode']) : $instance['countrycode']);
		$zoom = $instance['zoom'];
		$markercolor = $instance['markercolor'];
		$icon = $instance['icon'];
		$logo = $instance['logo'];
		$mapHTML = $instance['mapHTML'];
		$showinfo = (isset($instance['showinfo']) ? $instance['showinfo'] : FALSE);
		
		// Map Type Settings
		if ((empty($maptype)) || ($maptype == 'dynamic')) {
			$dynamicClass = '';
			$staticClass = 'hide'; 
		} else {
			$dynamicClass = 'hide';
			$staticClass = '';
		}
		
		// Number of Map Pins Settings
		$pinNumClass = (($markers > 1) ? 'multi' : 'single');
		
		// Map Style Select
		$mapStyles = comomap_get_styles();
		$styleOptions = ''; 
		if (is_array($mapStyles)) {
			$styleCount = count($mapStyles);
			for ($s=0;$s<$styleCount;$s++) {
				$styleOptions .= '<option value="'. $mapStyles[$s]['value'] .'"'. (($mapStyle == $mapStyles[$s]['value']) ? ' selected="selected"' : '' ) .'>'. $mapStyles[$s]['text'] .'</option>'; 
			}
		}
		
		$uploadClass = '';
		$removeClass = ''; 
		
		?>
		
		<div class="map-widget-form">
			<!-- Widget Title field -->
			<p><label for="<?=$this->get_field_id('title')?>">Title:</label> <input class="widefat" id="<?=$this->get_field_id('title')?>" name="<?=$this->get_field_name('title')?>" type="text" value="<?=$title?>" /></p>
			
			<input type="hidden" name="maps" value="<?=$maps?>" />
			
			<!-- Map ID -->
			<p><label for="<?=$this->get_field_id('mapid')?>">Map ID:</label> <input class="widefat" id="<?=$this->get_field_id('mapid')?>" name="<?=$this->get_field_name('mapid')?>" type="text" value="<?=$mapid?>" /></p>
			
			<!-- Number of Map Pins -->
			<p><label for="<?=$this->get_field_id('markers')?>">Number of Map Markers:</label> <input class="widefat mapPinNum" id="<?=$this->get_field_id('markers')?>" name="<?=$this->get_field_name('markers')?>" type="number" min="1" max="6" step="1" value="<?=$markers?>" /></p>
						
			<!-- Widget Map Type field -->
			<p><label for="<?=$this->get_field_id('maptype')?>">Type:</label> <select class="widefat mapType" id="<?=$this->get_field_id('maptype')?>" name="<?=$this->get_field_name('maptype')?>">
				<option value="dynamic" <?=(($maptype == 'dynamic') ? 'selected="selected"' : '')?>>Dynamic</option>
				<option value="static" <?=(($maptype == 'static') ? 'selected="selected"' : '')?>>Static</option>
			</select></p>
			<div class="static-map-settings <?=$staticClass?>">
				<!-- Map Scale -->
				<p><label for="<?=$this->get_field_id('mapscale')?>">Map Scale:</label> <input class="widefat" id="<?=$this->get_field_id('mapscale')?>" name="<?=$this->get_field_name('mapscale')?>" type="number" min="1" max="2" step="1" value="<?=$mapscale?>" /></p>
				<!-- Map Width -->
				<p><label for="<?=$this->get_field_id('width')?>">Width:</label> <input class="widefat" id="<?=$this->get_field_id('width')?>" name="<?=$this->get_field_name('width')?>" type="number" min="0" max="1920" step="1" value="<?=$width?>" /></p>
				<!-- Map Height -->
				<p><label for="<?=$this->get_field_id('height')?>">Height:</label> <input class="widefat" id="<?=$this->get_field_id('height')?>" name="<?=$this->get_field_name('height')?>" type="number" min="0" max="1920" step="1" value="<?=$height?>" /></p>
			</div>
			
			<!-- Map Style -->
			<p><label for="<?=$this->get_field_id('mapstyle')?>">Style:</label> <select class="widefat" id="<?=$this->get_field_id('mapstyle')?>" name="<?=$this->get_field_name('mapstyle')?>"><?=$styleOptions?></select></p>	
			
			<!-- Map Label -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('label')?>">Label:</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('label')?>" name="<?=$this->get_field_name('label')?>"><?=$label?></textarea>
				<span class"como-note"><em>Label displayed near map marker <span class="multi-message">Separate multiple values with line breaks</span></em></span>
			</p>
			
			<!-- Map Address -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('address')?>">Address:</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('address')?>" name="<?=$this->get_field_name('address')?>"><?=$address?></textarea>
				<span class"como-note"><em>Address Displayed in Map Popup <span class="multi-message">Separate multiple values with line breaks</span></em></span>
			</p>
			
			<!-- Map Latitude -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('lat')?>">Latitude:</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('lat')?>" name="<?=$this->get_field_name('lat')?>"><?=$lat?></textarea>
				<span class"como-note"><em>Address Latitude <span class="multi-message">Separate multiple values with line breaks</span></em></span>
			</p>
			
			<!-- Map Longitude -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('long')?>">Longitude:</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('long')?>" name="<?=$this->get_field_name('long')?>"><?=$long?></textarea>
				<span class"como-note"><em>Address Longitude <span class="multi-message">Separate multiple values with line breaks</span></em></span>
			</p>
			
			<!-- Map Center Latitude -->
			<p>
				<label for="<?=$this->get_field_id('centerlat')?>">Center Latitude:</label> 
				<input type="text" class="widefat" id="<?=$this->get_field_id('centerlat')?>" name="<?=$this->get_field_name('centerlat')?>" value="<?=$centerlat?>" />
				<span class"como-note"><em>Enter value here if you would like the map center to be different</em></span>
			</p>
			
			<!-- Map Center Longitude -->
			<p>
				<label for="<?=$this->get_field_id('centerlong')?>">Center Longitude:</label> 
				<input type="text" class="widefat" id="<?=$this->get_field_id('centerlong')?>" name="<?=$this->get_field_name('centerlong')?>" value="<?=$centerlong?>" />
				<span class"como-note"><em>Enter value here if you would like the map center to be different</span></em></span>
			</p>
			<!-- Google Map Link field -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('googlelink')?>">Google Map Link(s):</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('googlelink')?>" name="<?=$this->get_field_name('googlelink')?>"><?=$googlelink?></textarea>
				<span class="multi-message">Separate multiple values with line breaks</span>
			</p>
			<!-- Bing Map Link field -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('binglink')?>">Bing Map Link(s):</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('binglink')?>" name="<?=$this->get_field_name('binglink')?>"><?=$binglink?></textarea>
				<span class="multi-message">Separate multiple values with line breaks</span>
			</p>
			<!-- Map Phone Number -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('phone')?>">Phone Number:</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('phone')?>" name="<?=$this->get_field_name('phone')?>"><?=$phone?></textarea>
				<span class="multi-message">Separate multiple values with line breaks</span>
			</p>
			<!-- Map Country Code Number -->
			<p class="single-multi-field <?=$pinNumClass?>">
				<label for="<?=$this->get_field_id('countrycode')?>">Phone Country Code:</label> 
				<textarea class="widefat" id="<?=$this->get_field_id('countrycode')?>" name="<?=$this->get_field_name('countrycode')?>"><?=$countrycode?></textarea>
				<span class="multi-message">Separate multiple values with line breaks (default: 1)</span>
			</p>
			<!-- Map Zoom -->
			<p><label for="<?=$this->get_field_id('zoom')?>">Map Zoom Level:</label> <input class="widefat" id="<?=$this->get_field_id('zoom')?>" name="<?=$this->get_field_name('zoom')?>" type="number" min="1" max="25" step="1" value="<?=$zoom?>" /></p>
			<div class="dynamic-map-settings <?=$dynamicClass?>">
				<!-- Map Pin Icon -->
				<?php
					if ($icon != '') {
						$img_src = wp_get_attachment_image_src($icon, 'map-icon-image');
						$uploadClass = 'hide';
						$removeClass = ''; 
					} else {
						$have_icon_img = false;
						$uploadClass = '';
						$removeClass = 'hide';
					}
				?>
				<div>
					<input class="url-file" id="<?=$this->get_field_id('icon')?>" name="<?=$this->get_field_name('icon')?>" type="hidden" value="<?=$icon?>" />
					<p><label for="<?=$this->get_field_id('icon')?>">Map Icon:</label> <a href="#" class="<?=$uploadClass?> comomap-upload-image"><?php esc_html_e('Upload Image', 'comomap' ); ?></a>
					<a href="#" class="<?=$removeClass?> comomap-remove-image"><?php esc_html_e('Remove Image', 'comomap' ); ?></a></p>
					<div class="img-container img-preview">
						<?php if ( $icon ) : ?>
							<img src="<?=$img_src[0]?>" alt="" style="max-width:100%;" />
						<?php endif; ?>
					</div>
				</div>
				
				<!-- Map Logo -->
				<?php
					if ($logo) {
						$img_src = wp_get_attachment_image_src($logo, 'map-logo-image');
						$uploadClass = 'hide';
						$removeClass = ''; 
					} else {
						$have_logo_img = false;
						$uploadClass = '';
						$removeClass = 'hide';
					}
				?>
				<div>
					<input class="url-file" id="<?=$this->get_field_id('logo')?>" name="<?=$this->get_field_name('logo')?>" type="hidden" value="<?=$logo?>" />
					<p><label for="<?=$this->get_field_id('logo')?>">Map Logo:</label> <a href="#" class="<?=$uploadClass?> comomap-upload-image"><?php esc_html_e('Upload Image', 'comomap' ); ?></a>
					<a href="#" class="<?=$removeClass?> comomap-remove-image"><?php esc_html_e('Remove Image', 'comomap' ); ?></a></p>
					<div class="img-container img-preview">
						<?php if ( $logo ) : ?>
							<img src="<?=$img_src[0]?>" alt="" style="max-width:100%;" />
						<?php endif; ?>
					</div>
				</div>
				
				<!-- Map HTML -->
				<p><label for="<?=$this->get_field_id('mapHTML')?>">Map HTML:</label> <textarea class="widefat" id="<?=$this->get_field_id('mapHTML')?>" name="<?=$this->get_field_name('mapHTML')?>"><?=$mapHTML?></textarea></p>
				
				<!-- Map Show Info -->
				<p class="single-multi-field <?=$pinNumClass?>">
					<label for="<?=$this->get_field_id('showinfo')?>">Show Info Box By Default:</label> 
					<input type="checkbox" id="<?=$this->get_field_id('showinfo')?>" name="<?=$this->get_field_name('showinfo')?>" value="TRUE" />
					<span class="multi-message">Show map info box by default (default: FALSE)</span>
				</p>
				
			</div>
		</div>
		<script>
			jQuery('.mapType').on('change', function() {
				var selected = this.value;
				var thisForm = this.closest('.map-widget-form');
				if (selected === 'dynamic') {
					jQuery(thisForm).find('.static-map-settings').addClass('hide');
					jQuery(thisForm).find('.dynamic-map-settings').removeClass('hide');
				} else {
					jQuery(thisForm).find('.static-map-settings').removeClass('hide');
					jQuery(thisForm).find('.dynamic-map-settings').addClass('hide');
				}
			});
			jQuery('.mapPinNum').on('change', function() {
				var selected = this.value;
				var thisForm = this.closest('.map-widget-form');
				if (selected > 1) {
					jQuery(thisForm).find('.single-multi-field').addClass('multi');
					jQuery(thisForm).find('.single-multi-field').removeClass('single');
				} else {
					jQuery(thisForm).find('.single-multi-field').removeClass('multi');
					jQuery(thisForm).find('.single-multi-field').addClass('single');
				}
			});
		</script>
		
     	<?php 
	}
     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    	$instance['title'] = $new_instance['title'];
     	$instance['maps'] = $new_instance['maps'];
		$instance['markers'] = $new_instance['markers'];
		$instance['maptype'] = $new_instance['maptype']; 
		$instance['mapstyle'] = $new_instance['mapstyle'];
		$instance['mapid'] = $new_instance['mapid'];
		$instance['mapscale'] = $new_instance['mapscale'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['label'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['label']) : $new_instance['label']);
		$instance['address'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['address']) : $new_instance['address']);
		$instance['lat'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['lat']) : $new_instance['lat']);
		$instance['long'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['long']): $new_instance['long']);
		$instance['centerlat'] = $new_instance['centerlat'];
		$instance['centerlong'] = $new_instance['centerlong'];
		$instance['googlelink'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['googlelink']) : $new_instance['googlelink']);
		$instance['binglink'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['binglink']) : $new_instance['binglink']);
		$instance['phone'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['phone']) : $new_instance['phone']);
		$instance['countrycode'] = (($new_instance['markers']>1) ? explode(PHP_EOL, $new_instance['countrycode']) : $new_instance['countrycode']);
		$instance['zoom'] = $new_instance['zoom'];
		$instance['markercolor'] = $new_instance['markercolor'];
		$instance['icon'] = $new_instance['icon'];
		$instance['logo'] = $new_instance['logo'];
		$instance['mapHTML'] = $new_instance['mapHTML'];
		$instance['showinfo'] = ((isset($instance['showinfo'])) ? $new_instance['mapHTML'] : FALSE);
    	return $instance;
	}
} // Class como_map_widget ends here
/********* TinyMCE Button Add-On ***********/
add_action( 'after_setup_theme', 'comomap_button_setup' );
if (!function_exists('comomap_button_setup')) {
    function comomap_button_setup() {
        add_action( 'init', 'comomap_button' );
    }
}
if ( ! function_exists( 'comomap_button' ) ) {
    function comomap_button() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }
        add_filter( 'mce_external_plugins', 'comomap_add_buttons' );
        add_filter( 'mce_buttons', 'comomap_register_buttons' );
    }
}
if ( ! function_exists( 'comomap_add_buttons' ) ) {
    function comomap_add_buttons( $plugin_array ) {
        $plugin_array['comomapButton'] = plugin_dir_url( __FILE__ ) .'js/tinymce_map_button.js';
        return $plugin_array;
    }
}
if ( ! function_exists( 'comomap_register_buttons' ) ) {
    function comomap_register_buttons( $buttons ) {
        array_push( $buttons, 'comomapButton' );
        return $buttons;
    }
}
add_action ( 'after_wp_tiny_mce', 'comomap_tinymce_extra_vars' );
if ( !function_exists( 'comomap_tinymce_extra_vars' ) ) {
	function comomap_tinymce_extra_vars() { 
		// Get map Styles
		$mapStyles = comomap_get_styles();
		$mapStyles = json_encode($mapStyles);
		?>
		<script type="text/javascript">
			var tinyMCE_map = <?php echo json_encode(
				array(
					'button_name' => esc_html__('Embed Map', 'comomap'),
					'button_title' => esc_html__('Embed Map', 'comomap'),
					'map_style_select_options' => $mapStyles,
					'icon_button_title' => esc_html__('Upload Map Icon', 'comomap'),
					'logo_button_title' => esc_html__('Upload Map Logo', 'comomap'),
				)
			);
			?>;
		</script><?php
	} 	
}
/********* TinyMCE Button Add-On - Dynamic Map ***********/
/*add_action( 'after_setup_theme', 'comomap_button_setup' );
if (!function_exists('comomap_button_setup')) {
    function comomap_button_setup() {
        add_action( 'init', 'comomap_button' );
    }
}
if ( ! function_exists( 'comomap_button' ) ) {
    function comomap_button() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }
        add_filter( 'mce_external_plugins', 'comomap_add_buttons' );
        add_filter( 'mce_buttons', 'comomap_register_buttons' );
    }
}
if ( ! function_exists( 'comomap_add_buttons' ) ) {
    function comomap_add_buttons( $plugin_array ) {
        $plugin_array['comomapButton'] = plugin_dir_url( __FILE__ ) .'js/tinymce_map_button.js';
        return $plugin_array;
    }
}
if ( ! function_exists( 'comomap_register_buttons' ) ) {
    function comomap_register_buttons( $buttons ) {
        array_push( $buttons, 'comomapButton' );
        return $buttons;
    }
}
add_action ( 'after_wp_tiny_mce', 'comomap_tinymce_extra_vars' );
if ( !function_exists( 'comomap_tinymce_extra_vars' ) ) {
	function comomap_tinymce_extra_vars() { 
		// Get map Styles
		$mapStyles = comomap_get_styles();
		$mapStyles = json_encode($mapStyles);
		?>
		<script type="text/javascript">
			var tinyMCE_map = <?php echo json_encode(
				array(
					'button_name' => esc_html__('Embed Map', 'comomap'),
					'button_title' => esc_html__('Embed Map', 'comomap'),
					'map_style_select_options' => $mapStyles,
					'icon_button_title' => esc_html__('Upload Map Icon', 'comomap'),
					'logo_button_title' => esc_html__('Upload Map Logo', 'comomap'),
				)
			);
			?>;
		</script><?php
	} 	
}*/
?>