<?php
/**
 * Provide the view for a metabox
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Como_Map
 * @subpackage 	Como_Map/admin/partials
 */
wp_nonce_field( $this->plugin_name, 'candidate_requirements_nonce' );
$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'candidate-requirements-skills';
$atts['label'] 			= 'Skills/Qualifications';
$atts['settings']['textarea_name'] = 'candidate-requirements-skills';
$atts['value'] 			= '';
if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	$atts['value'] = $this->meta[$atts['id']][0];
}
apply_filters( $this->plugin_name . '-field-candidate-requirements-skills', $atts );
?><p><?php
include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' );
?></p><?php
$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'candidate-requirements-education';
$atts['label'] 			= 'Education';
$atts['settings']['textarea_name'] = 'candidate-requirements-education';
$atts['value'] 			= '';
if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	$atts['value'] = $this->meta[$atts['id']][0];
}
apply_filters( $this->plugin_name . '-field-candidate-requirements-education', $atts );
?><p><?php
include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' );
?></p><?php
$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'candidate-requirements-experience';
$atts['label'] 			= 'Experience';
$atts['settings']['textarea_name'] = 'candidate-requirements-experience';
$atts['value'] 			= '';
if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	$atts['value'] = $this->meta[$atts['id']][0];
}
apply_filters( $this->plugin_name . '-field-candidate-requirements-experience', $atts );
?><p><?php
include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' );
?></p>
