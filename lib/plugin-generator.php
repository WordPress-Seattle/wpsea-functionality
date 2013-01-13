<?php

class WPSea_Plugin_Generator {
	const DEFAULT_PLUGIN_VERSION = '0.1';

	public $page_id = 'wpsea_plugin_generator_settings';
	public $error_id = 'wpsea_plugin_generator_errors';
	public $section = array();
	public $field = array();

	public function __construct() {


		$this->section['HEADER'] = array(
		  'ID' => 'wpsea_plugin_header_section'
		  ,'LABEL' => 'Plugin Header'
		  ,'FIELDS' => array('PLUGIN_NAME', 'PLUGIN_VERSION', 'PLUGIN_URI', 'PLUGIN_DESC', 'AUTHOR_NAME', 'AUTHOR_URI')
		);

		$this->section['HOOKS'] = array(
		  'ID' => 'wpsea_plugin_hooks_section'
		  ,'LABEL' => 'Common Hooks'
		  ,'FIELDS' => array('ACTIVATION', 'DEACTIVATION', 'UNINSTALL')
		);

		$this->section['ACTIONS'] = array(
		  'ID' => 'wpsea_plugin_hooks_section'
		  ,'LABEL' => 'Common Hooks'
		  ,'FIELDS' => array('INIT', 'ADMIN_MENU', 'ADMIN_INIT', 'WIDGETS_INIT', 'WP_HEAD', 'WP_ENQUEUE_SCRIPTS', 'WP_FOOTER')
		);

		//
		// Fields
		//
		$this->field['PLUGIN_NAME'] = array(
			'ID' => 'plugin_name'
			, 'LABEL' => 'Plugin Name'
			, 'TYPE' => 'text'
		);
	
		$this->field['PLUGIN_VERSION'] = array(
			'ID' => 'plugin_version'
			, 'LABEL' => 'Plugin Version'
			, 'TYPE' => 'text'
		);

		$this->field['PLUGIN_URI'] = array(
			'ID' => 'plugin_uri'
			, 'LABEL' => 'Plugin URI'
			, 'TYPE' => 'text'
		);

		$this->field['PLUGIN_DESC'] = array(
			'ID' => 'plugin_description'
			, 'LABEL' => 'Plugin Description'
			, 'TYPE' => 'textarea'
		);

		$this->field['AUTHOR_NAME'] = array(
			'ID' => 'author_name'
			, 'LABEL' => 'Author Name'
			, 'TYPE' => 'text'
		);
	
		$this->field['AUTHOR_URI'] = array(
			'ID' => 'author_uri'
			, 'LABEL' => 'Author URI'
			, 'TYPE' => 'url'
		);
        // ---------------------------------------------------------------------

		$this->field['ACTIVATION'] = array(
			'ID' => 'register_activation_hook'
			, 'LABEL' => 'Activation Hook'
			, 'TYPE' => 'checkbox'
		);
	
		$this->field['DEACTIVATION'] = array(
			'ID' => 'register_deactivation_hook'
			, 'LABEL' => 'De-Activation Hook'
			, 'TYPE' => 'checkbox'
		);
	
		$this->field['UNINSTALL'] = array(
			'ID' => 'register_uninstall_hook'
			, 'LABEL' => 'Un-Install Hook'
			, 'TYPE' => 'checkbox'
		);

        // ---------------------------------------------------------------------
		// actions
		// init, admin_menu, admin_init, widgets_init, wp_head, wp_enqueue_scripts, wp_footer

		$this->field['INIT'] = array(
			'ID' => 'init'
			, 'LABEL' => 'Init Action'
			, 'TYPE' => 'checkbox'
		);

		$this->field['ADMIN_INIT'] = array(
			'ID' => 'admin_init'
			, 'LABEL' => 'Admin Init Action'
			, 'TYPE' => 'checkbox'
		);

		$this->field['ADMIN_MENU'] = array(
			'ID' => 'admin_menu'
			, 'LABEL' => 'Admin Menu Action'
			, 'TYPE' => 'checkbox'
		);

		$this->field['WIDGETS_INIT'] = array(
			'ID' => 'widgets_init'
			, 'LABEL' => 'Widgets Init Action'
			, 'TYPE' => 'checkbox'
		);

		$this->field['WP_HEAD'] = array(
			'ID' => 'wp_head'
			, 'LABEL' => 'WP Head Action'
			, 'TYPE' => 'checkbox'
		);

		$this->field['WP_ENQUEUE_SCRIPTS'] = array(
			'ID' => 'wp_enqueue_scripts'
			, 'LABEL' => 'WP Enqueue Scripts Action'
			, 'TYPE' => 'checkbox'
		);

		$this->field['WP_FOOTER'] = array(
			'ID' => 'wp_footer'
			, 'LABEL' => 'WP Footer Action'
			, 'TYPE' => 'checkbox'
		);


		add_action( 'admin_menu', array( &$this, 'update_menu' ) );	
	}

	public function build_fields( $section ){

		$this->necho("<fieldset>");
		$this->necho("<legend>{$section}</legend>");
		foreach ( $this->section[ $section ]['FIELDS'] AS $field ){
			$data = $this->field[$field];

			$this->necho('<div>');
			$value = ( isset( $_POST[ $data['ID'] ] ) ) ? $_POST[ $data['ID'] ]: '';
			switch ( $data['TYPE'] ){
			case "text":
				$this->text_field( $data['ID'], $data['LABEL'], $value );
				break;

			case "textarea":
				$this->textarea_field( $data['ID'], $data['LABEL'], $value );
				break;

			case "url":
				$this->url_field( $data['ID'], $data['LABEL'], $value );
				break;

			case "checkbox":
				$this->checkbox_field( $data['ID'], $data['LABEL'], $value );
				break;

			default:
				//throw new Exception( "The field type '" . $d['TYPE'] . "' is not supported." )
				break;

			}
			$this->necho('</div>');
		}
		$this->necho("<br />");
		$this->necho("<br />");
		$this->necho("</fieldset>");


	}


	/**
	* Validation Functions
	*/
	public function validate_plugin_name( $input ){

		$empty_mesg = 'Whoops! You need a value for "%s"';
		$bad_version_mesg = 'Your value for "%s" does not look like a version.  try #.##';
		$valid = array();
		
		if ( ! empty( $input['plugin_name'] ) ) {
			$output['plugin_name'] = $input['plugin_name'];
		} else {
			$empty_plugin_name = sprintf( $empty_mesg, $this->section['PLUGIN_NAME']['LABEL'] );
			add_settings_error( $this->error_id, 'plugin_name', $empty_plugin_name );
		}
		return $valid;
	}

	public function validate_version( $input ){
		if ( preg_match("/\d(.\d)*/", $input['plugin_version'] ) ){
			$output['plugin_version'] = $input['plugin_version'];
		} else {
			$empty_plugin_name = sprintf( $bad_version_mesg, $this->section['PLUGIN_VERSION']['LABEL'] );
			add_settings_error( $this->error_id, 'plugin_version', $empty_plugin_name );
		}

	    return $output;
	}

	
	public function text_field($id, $label, $value) {
		echo '<label for="' . $id  . '" />' . $label . '</label>';
		echo '<input type="text" name="' . $id  . '" id="' . $id . '" value="' . $value . '" />';
	}

	public function textarea_field($id, $label, $value) {
		echo '<label for="' . $id  . '" />' . $label . '</label>';
		echo '<textarea name="' . $id . '" id="' . $id . '">' . $value . '</textarea>';
	}


	public function url_field($id, $label, $value) {
		echo '<label for="' . $id  . '" />' . $label . '</label>';
		echo '<input type="url" name="' . $id . '" id="' . $id . '" value="' . $value . '" />';
	}

	public function checkbox_field($id, $label, $value) {
		echo '<input type="checkbox" name="' . $id . '" id="' . $id . '" value="' . $value . '" /> ';
		echo '<label for="' . $id  . '" />' . $label . '</label><br />';
	}




	// --------------------------------------------------------------------------------

	public function header_section_callback() {
		echo '<p>Fill in this section to create the primary info about your plugin '
		. ' The more complete it is, the better prepared you will be when you submit it to ' 
		. ' wordpress.org. </p>';
	}

	public function hooks_section_callback() {
		echo '<p>There are many hooks that WordPress. In this section, are some
		of the more common hooks. Check on the ones you know you need.</p>';
	}



	// --------------------------------------------------------------------------------

	public function update_menu() {
		add_options_page(
			'Plugin Generator', 
			'Plugin Generator', 
			'manage_options', 
			$this->page_id, 
			array(&$this, 'settings_page') 
		);
	}

	public function settings_page() {

		$licenses = array(
			"--" => '-No License-'
			,"APACHE" => 'Apache License'
			,"BSD" => 'BSD License'
			,"CREATIVE-COMMONS" => 'Creative Commons'
			,"GPLv2" => 'GNU Public License v2'
			,"GPLv3" => 'GNU Public License v3'
			,"MIT" => 'MIT License'
			,"PRIVATE" => 'Private'
		);


	?>
		<div id="theme-options-wrap" class="wrap">    
		<?php screen_icon(); ?>
		<h2>WPSea Plugin Generator</h2>
		<form action="" method="post" accept-charset="utf-8">

		<?php 
			$this->build_fields('HEADER');
			$this->build_fields('HOOKS');
			$this->build_fields('ACTIONS');
		?>

			<div>
				<input type="submit" class="button-primary" id="wpsea_create_plugin" name="wpsea_create_plugin" value="Generate" />
			</div>
		</form> 
		</div>
	<?php

		$header = '';
		if ( isset( $_POST['wpsea_create_plugin'] ) ){
			$this->build_file();		
		}

	}

	public function to_slug($str, $sep = '-'){
		$slug = strtolower(trim($str));
		$slug = preg_replace('/[\s\-\_]+/', $sep, $slug); 

		return $slug;
	}


	public function build_file(){

		$prefix = $this->to_slug($_POST['plugin_name'], '_') . '_';
		$plugin_dir = WP_PLUGIN_DIR . '/' . $this->to_slug($_POST['plugin_name']); 
		$filename = $plugin_dir . '/' . $this->to_slug($_POST['plugin_name']) . '.php'; 

		if (! file_exists($plugin_dir)) {
			if (  ! mkdir($plugin_dir, 0755)) {
				error_log('COULD NOT CREATE DIRECTORY ' . $plugin_dir);
			} 
		}

		$fh = fopen($filename, 'w+');
		if (! $fh) {
			error_log('COULD NOT OPEN ' . $filename);
			return;
		} 
		
		$header  = "<?php\n\n/*\n";		
		if ( isset( $_POST['plugin_name'] ) ) {
			$header .= $this->ret( "Plugin Name: {$_POST['plugin_name']}" );
		}
		
		if ( isset( $_POST['plugin_description'] ) ) {
			$header .= $this->ret( "Description: {$_POST['plugin_description']}" );
		}

		if ( isset( $_POST['plugin_uri'] ) ) {
			$header .= $this->ret( "Plugin URI: {$_POST['plugin_uri']}" );
		}

		if ( isset( $_POST['plugin_version'] ) ) {
			$header .= $this->ret( "Version: {$_POST['plugin_version']}" );
		}

		if ( isset( $_POST['author_name'] ) ) {
			$header .= $this->ret( "Author: {$_POST['author_name']}" );
		}

		if ( isset( $_POST['author_uri'] ) ) {
			$header .= $this->ret( "Author URI: {$_POST['author_uri']}" );
		}

		$header .= $this->ret( "*/" );		
		fwrite($fh, $header );


		$all_functions = '';
		$all_hooks = '';

		$all_functions .= "\n/*\n\tHooks\n*/\n\n";
		$all_hooks .= "\n\n/*\n\tActions\n*/\n\n" ;

		if ( isset( $_POST['register_activation_hook'] ) ) {
			$all_functions .= "function {$prefix}register_activation_hook(){\n}\n\n" ;
			$all_hooks .= 'register_activation_hook( __FILE__, ' .  "'{$prefix}register_activation_hook'" .   ' );' . "\n";
		}
	
		if ( isset( $_POST['register_deactivation_hook'] ) ) {
			$all_functions .= "function {$prefix}register_deactivation_hook(){\n}\n\n" ;
			$all_hooks .= 'register_deactivation_hook( __FILE__, ' .  "'{$prefix}register_deactivation_hook'" .   ' ); ' . "\n";
		}
	
		if ( isset( $_POST['register_uninstall_hook'] ) ) {
			$all_functions .= "function {$prefix}register_uninstall_hook(){\n}\n\n" ;
			$all_hooks .= 'register_uninstall_hook( __FILE__, ' .  "'{$prefix}register_uninstall_hook'" .   ' ); ' . "\n";
		}


		if ( isset( $_POST['init'] ) ) {
			$all_functions .= "function {$prefix}init(){\n}\n\n" ;
			$all_hooks .= "add_action( 'init', '{$prefix}init' );\n";
		}
	
		if ( isset( $_POST['admin_init'] ) ) {
			$all_functions .= "function {$prefix}admin_init(){\n}\n\n" ;
			$all_hooks .= "add_action( 'admin_init', '{$prefix}admin_init' );\n";
		}
	
		if ( isset( $_POST['admin_menu'] ) ) {
			$all_functions .= "function {$prefix}admin_menu(){\n}\n\n" ;
			$all_hooks .= "add_action( 'admin_menu', '{$prefix}admin_menu' );\n";
		}

		if ( isset( $_POST['widgets_init'] ) ) {
			$all_functions .= "function {$prefix}widgets_init(){\n}\n\n" ;
			$all_hooks .= "add_action( 'widgets_init', '{$prefix}widgets_init' );\n";
		}

		if ( isset( $_POST['wp_head'] ) ) {
			$all_functions .= "function {$prefix}wp_head(){\n}\n\n" ;
			$all_hooks .= "add_action( 'wp_head', '{$prefix}wp_head' );\n";
		}

		if ( isset( $_POST['wp_enqueue_scripts'] ) ) {
			$all_functions .= "function {$prefix}wp_enqueue_scripts(){\n}\n\n" ;
			$all_hooks .= "add_action( 'wp_enqueue_scripts', '{$prefix}wp_enqueue_scripts' );\n";
		}

		if ( isset( $_POST['wp_footer'] ) ) {
			$all_functions .= "function {$prefix}wp_footer(){\n}\n\n" ;
			$all_hooks .= "add_action( 'wp_footer', '{$prefix}wp_footer' );\n";
		}

		fwrite( $fh, $all_hooks );
		fwrite( $fh, $all_functions );
	}

	public function make_function( $hook_name ){
		if ( isset( $_POST[ $hook_name ] ) ) {
			fwrite( $fh, "function {$prefix}{$hook_name}(){\n\n}\n" );
		}

	}

	public function necho( $str ){
		echo $str . "\n";
	}

	public function ret( $str ){
		return $str . "\n";
	}


}

$wpsea_func_plugin_generator = new Wpsea_Plugin_Generator();


