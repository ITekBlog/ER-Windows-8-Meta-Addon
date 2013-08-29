<?php
/*
Plugin Name: ER Windows 8 Meta Addon
Plugin URI: http://itekblog.com/wordpress-plugins/er-windows-8-meta-addon/
Description: Easily upload your logo and add the meta required to show your website as tile in Windows 8 Metro.
Version: 1.0.0
Author: ER (ET & RaveMaker)
Author URI: http://itekblog.com
License: GPL3
Copyright 2013 ER
*/

define( 'er_win8_meta_PLUGIN_NAME', 'ER Windows 8 META' );
define( 'er_win8_meta_CONTACT_EMAIL', 'wordpress@itekblog.com' );
define( 'er_win8_meta_OPTION_PAGE', 'er-win8-meta' );
define( 'er_win8_meta_OPTION_SECTION', 'er_win8_meta_option_section' );
define( 'er_win8_meta_OPTION_GROUP', 'er_win8_meta_option_group' );
define( 'er_win8_meta_OPTION_PNG_FILE', 'er_win8_meta_option-PNG_file' );
define( 'er_win8_meta_OPTION_PNG_URL', 'er_win8_meta_option-PNG_url' );
define( 'er_win8_meta_OPTION_PNG_ORIGINAL_FILE', 'er_win8_meta_option-PNG_original_file' );
define( 'er_win8_meta_OPTION_PNG_ORIGINAL_URL', 'er_win8_meta_option-PNG_original_url' );
define( 'er_win8_meta_OPTION_TILE_COLOR', 'er_win8_meta_option-TILE_COLOR' );

// for deletion
//define( 'er_win8_meta_OPTION_TILE_COLOR_URL', 'er_win8_meta_option-TILE_COLOR' );
//define( 'er_win8_meta_OPTION_TILE_COLOR_ORIGINAL_FILE', 'er_win8_meta_option-TILE_COLOR_original_file' );
//define( 'er_win8_meta_OPTION_TILE_COLOR_ORIGINAL_URL', 'er_win8_meta_option-TILE_COLOR_original_url' );

add_action( 'admin_menu', 'er_win8_meta_admin_menu' );
function er_win8_meta_admin_menu() {
	add_options_page( 'ER Windows 8 Meta', 'ER Windows 8 Meta', 'manage_options', er_win8_meta_OPTION_PAGE, 'er_win8_meta_add_options_page_PNG' );
}

add_action( 'admin_init', 'er_win8_meta_register_settings' );
function er_win8_meta_register_settings() { //er_win8_meta_OPTION_PNG_URL
	register_setting( er_win8_meta_OPTION_GROUP, er_win8_meta_OPTION_GROUP, 'er_win8_meta_sanitize_option_PNG_url' );
	add_settings_section(
		er_win8_meta_OPTION_SECTION,
		'Upload your tile image in PNG format',
		'er_win8_meta_settings_section_display',
		er_win8_meta_OPTION_PAGE
	);
	add_settings_field(
		er_win8_meta_OPTION_PNG_URL,
		'Upload Tile Image',
		'er_win8_meta_settings_field_display',
		er_win8_meta_OPTION_PAGE,
		er_win8_meta_OPTION_SECTION
	);
	add_settings_field(
		er_win8_meta_OPTION_TILE_COLOR,
		'Input Tile Color',
		'er_win8_meta_settings_field_display_touch',
		er_win8_meta_OPTION_PAGE,
		er_win8_meta_OPTION_SECTION
	);
}
function er_win8_meta_settings_section_display() { ?>
<p style="padding:0px 7px 0px 7px;">
	<ul style="list-style: disc; margin-left: 40px;">
		<li>Upload an image file to use as your Windows 8 Metro Tile.</li>
		<li>Required image type is PNG only.</li>
		<li>Required dimensions: 144x144px</li>
	</ul>
</p>
<?php }
function er_win8_meta_sanitize_option_PNG_url($value) {
	$options = get_option( er_win8_meta_OPTION_GROUP, array() );	
	if ( isset( $_FILES['file-' . er_win8_meta_OPTION_PNG_URL] ) && $_FILES['file-' . er_win8_meta_OPTION_PNG_URL]['error'] == 0 ) {
		$file = $_FILES['file-' . er_win8_meta_OPTION_PNG_URL];
		$uploaded = wp_handle_upload( $file, array( 'test_form' => false, 'test_upload' => false ) );
		$ext = pathinfo( $uploaded['file'], PATHINFO_EXTENSION );
		
		if ( !empty( $uploaded['error'] ) ) {
			// TODO: Handle upload error?
		} else {
			$PNG_file = $uploaded['file'];
			$PNG_url = substr( $uploaded['url'], 0, strrpos( $uploaded['url'], '.' ) + 1 ) . 'png';
			$options = array_merge( $options, array(
				er_win8_meta_OPTION_PNG_FILE => $PNG_file,
				er_win8_meta_OPTION_PNG_URL => $PNG_url,
				er_win8_meta_OPTION_PNG_ORIGINAL_FILE => $uploaded['file'],
				er_win8_meta_OPTION_PNG_ORIGINAL_URL => $uploaded['url'],
			) );
		}
	}
	
	if ( !empty($_POST[er_win8_meta_OPTION_TILE_COLOR] )) {
		$TILE_COLOR = $_POST[er_win8_meta_OPTION_TILE_COLOR];
		$options = array_merge( $options, array(
			er_win8_meta_OPTION_TILE_COLOR => $TILE_COLOR,
		) );
	}
	
	return $options;
}

function er_win8_meta_settings_field_display() {
	$options = get_option( er_win8_meta_OPTION_GROUP );	
?>
<input id="<?php echo er_win8_meta_OPTION_PNG_URL; ?>" name="<?php echo er_win8_meta_OPTION_GROUP; ?>[<?php echo er_win8_meta_OPTION_PNG_URL; ?>]" size="40" type="text" value="<?php echo $options[er_win8_meta_OPTION_PNG_URL]; ?>" disabled="disabled" />
<div>
	<input id="file-<?php echo er_win8_meta_OPTION_PNG_URL; ?>" name="file-<?php echo er_win8_meta_OPTION_PNG_URL; ?>" size="40" type="file" style="position:absolute; top:0; left:0; opacity:0; -moz-opacity:0; width:120px; filter:alpha(opacity: 0); z-index:2; cursor:pointer;" />
	<input type="button" value="Upload Image" class="button" id="er_win8_meta_btn_upload" />
</div>
<br />
<?php
}

function er_win8_meta_settings_field_display_touch() {
	$options = get_option( er_win8_meta_OPTION_GROUP );
?>
<input id="<?php echo er_win8_meta_OPTION_TILE_COLOR; ?>" name="<?php echo er_win8_meta_OPTION_TILE_COLOR; ?>" type="text" value="<?php echo $options[er_win8_meta_OPTION_TILE_COLOR]; ?>" />
<br />
Example: Black is #000000
<?php if (!empty($options[er_win8_meta_OPTION_PNG_URL])): ?>
<br /><br /><br />
Color & Logo Test:
<div>
	<img style="padding:35px; background-color:<?php echo $options[er_win8_meta_OPTION_TILE_COLOR]; ?>;" src="<?php echo $options[er_win8_meta_OPTION_PNG_ORIGINAL_URL]; ?>" width="144" height="144" alt="PNG" />
</div>
<?php endif; ?>
<?php
}

function er_win8_meta_add_options_page_PNG() {
?>
<div class="wrap">
	<h2>ER Windows 8 Meta</h2>
	<div class="metabox-holder has-right-sidebar">
		<div id="post-body">
			<div id="post-body-content">
				<form method="post" action="options.php" enctype="multipart/form-data">
					<?php
						settings_fields( er_win8_meta_OPTION_GROUP );
						do_settings_sections( er_win8_meta_OPTION_PAGE );
					?>
					<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#file-<?php echo er_win8_meta_OPTION_PNG_URL; ?>').change(function() {
		$('#<?php echo er_win8_meta_OPTION_PNG_URL; ?>').val($(this).val().replace('C:\\fakepath\\', ''));
	});
	$('#<?php echo er_win8_meta_OPTION_TILE_COLOR; ?>').change(function() {
		$('#<?php echo er_win8_meta_OPTION_TILE_COLOR; ?>').val($(this).val().replace('C:\\fakepath\\', ''));
	});
	
	
	$('#form-comments').ajaxForm({
		target: '#target-comments'
	}); 
});
</script>
<?php
}

add_action( 'wp_head', 'er_win8_meta_wp_head_PNG' );
add_action( 'add_action', 'er_win8_meta_wp_head_PNG' );
function er_win8_meta_wp_head_PNG() {
	$options = get_option( er_win8_meta_OPTION_GROUP );
	if ( !empty( $options[er_win8_meta_OPTION_PNG_URL] ) ) {
		echo sprintf(
			'<meta name="msapplication-TileImage" content="%s" />',
			$options[er_win8_meta_OPTION_PNG_URL]
		);
	}
	if ( !empty( $options[er_win8_meta_OPTION_TILE_COLOR] ) ) {
		echo sprintf(
			'<meta name="msapplication-TileColor" content="%s" />',
			$options[er_win8_meta_OPTION_TILE_COLOR]
		);
	}
}

add_action( 'admin_enqueue_scripts', 'er_win8_meta_admin_enqueue_scripts' );
function er_win8_meta_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery-form' );
}