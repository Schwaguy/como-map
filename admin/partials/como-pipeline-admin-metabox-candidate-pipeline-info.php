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
?>
<div class="map-details">
	<div class="row">
		<div class="col-12 col-md-6 col-xl-2">
			<?php
				wp_nonce_field( $this->plugin_name, 'candidate_map_info' );
				$atts 					= array();
				$atts['class'] 			= '';
				$atts['description'] 	= '';
				$atts['id'] 			= 'candidate-color';
				$atts['label'] 			= 'Color';
				$atts['name'] 			= 'candidate-color';
				$atts['placeholder'] 	= '';
				$atts['type'] 			= 'text';
				$atts['value'] 			= '';
				if ( ! empty( $this->meta[$atts['id']][0] ) ) {
					$atts['value'] = $this->meta[$atts['id']][0];
				}
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			?>
			<p><?php include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-colorpicker.php'); ?></p>
		</div>
		<div class="col-12 col-md-6 col-xl-5">
			<?php
				wp_nonce_field( $this->plugin_name, 'candidate_map_info' );
				$atts 					= array();
				$atts['class'] 			= '';
				$atts['description'] 	= '';
				$atts['id'] 			= 'candidate-class';
				$atts['label'] 			= 'Class';
				$atts['name'] 			= 'candidate-class';
				$atts['placeholder'] 	= '';
				$atts['type'] 			= 'text';
				$atts['value'] 			= '';
				if ( ! empty( $this->meta[$atts['id']][0] ) ) {
					$atts['value'] = $this->meta[$atts['id']][0];
				}
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			?>
			<p><?php include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php'); ?></p>
		</div>
		<div class="col-12 col-md-6 col-xl-5">
			<?php
				$atts 					= array();
				$atts['class'] 			= '';
				$atts['description'] 	= 'Percentage of Development Process Completed';
				$atts['id'] 			= 'candidate-progress';
				$atts['label'] 			= 'Progress';
				$atts['name'] 			= 'candidate-progress';
				$atts['placeholder'] 	= '';
				$atts['type'] 			= 'number';
				$atts['value'] 			= '';
				$atts['min'] 			= '0';
				$atts['max'] 			= '100';
				$atts['step'] 			= '0.01';
				if ( ! empty( $this->meta[$atts['id']][0] ) ) {
					$atts['value'] = $this->meta[$atts['id']][0];
				}
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			?>
			<p><?php include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-number.php'); ?></p>
		</div>
		<div class="col-12 col-md-6 col-xl-6">
			<?php
				wp_nonce_field( $this->plugin_name, 'candidate_map_info' );
				$atts 					= array();
				$atts['class'] 			= '';
				$atts['description'] 	= '';
				$atts['id'] 			= 'candidate-link';
				$atts['label'] 			= 'Link';
				$atts['name'] 			= 'candidate-link';
				$atts['placeholder'] 	= '';
				$atts['type'] 			= 'text';
				$atts['value'] 			= '';
				if ( ! empty( $this->meta[$atts['id']][0] ) ) {
					$atts['value'] = $this->meta[$atts['id']][0];
				}
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			?>
			<p><?php include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php'); ?></p>
		</div>
		<div class="col-12 col-md-6 col-xl-6">
			<?php
				wp_nonce_field( $this->plugin_name, 'candidate_map_info' );
				$atts 					= array();
				$atts['class'] 			= '';
				$atts['description'] 	= '';
				$atts['id'] 			= 'candidate-progress-text';
				$atts['label'] 			= 'Progress Text';
				$atts['name'] 			= 'candidate-progress-text';
				$atts['placeholder'] 	= '';
				$atts['type'] 			= 'text';
				$atts['value'] 			= '';
				if ( ! empty( $this->meta[$atts['id']][0] ) ) {
					$atts['value'] = $this->meta[$atts['id']][0];
				}
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			?>
			<p><?php include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php'); ?></p>
		</div>
	</div>
	<div class="row">
		<?php
			$options = get_option('como-map-options');
			$columns = $options['map-columns'];
			foreach ($columns as $column) {
				if (($column['column-type'] !== 'title-column') && ($column['column-type'] !== 'progress-column')) {
					
					?><div class="col-12 col-md-6"><?php
					if ($column['column-type'] == 'image-column') {
						$atts 						= array();
						$atts['class'] 				= 'widefat url-file';
						$atts['id'] 				= $column['column-id'];
						$atts['label'] 				= $column['column-name'];
						$atts['label-add'] 			= 'Add Image';
						$atts['label-edit'] 		= 'Edit Image';
						$atts['label-header'] 		= 'Image Name';
						$atts['label-remove'] 		= 'Remove Image';
						$atts['label-upload'] 		= 'Choose/Upload Image';
						//$atts['name'] 				= "candidate-columns['". $column['column-id'] ."]";
						$atts['name'] 				= $column['column-id'];
						$atts['placeholder'] 		= '';
						$atts['type'] 				= 'hidden';
						$atts['value'] 				= '';
						if ( ! empty( $this->meta[$atts['id']][0] ) ) {
							$atts['value'] = $this->meta[$atts['id']][0];
						}
						apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
						include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-image-upload.php' ); 
					} else {
						//wp_nonce_field( $this->plugin_name, 'candidate_map_info' );
						$atts 					= array();
						$atts['class'] 			= '';
						$atts['description'] 	= '';
						$atts['id'] 			= $column['column-id'];
						$atts['label'] 			= $column['column-name'];
						//$atts['name'] 			= "candidate-columns['". $column['column-id'] ."]";
						$atts['name'] 			= $column['column-id'];
						$atts['placeholder'] 	= $column['column-name'];
						$atts['type'] 			= 'text';
						$atts['value'] 			= '';
						if ( ! empty( $this->meta[$atts['id']][0] ) ) {
							$atts['value'] = $this->meta[$atts['id']][0];
						}
						apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
						?><p><?php include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php'); ?></p><?php
					}
					?></div><?php
				}
			}
		?>
	</div>
</div><!-- map-details -->