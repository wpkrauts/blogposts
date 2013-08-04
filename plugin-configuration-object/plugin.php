<?php # -*- coding: utf-8 -*-
namespace Wpkrauts;
/**
 * Plugin Name: Wpkrauts Base Plugin
 * Description: Object-oriented plugin structure demo.
 * Plugin URI:  http://wpkrauts.com/2013/initialize-a-plugin-with-a-configuration-object/
 * Version:     2013.07.27
 * Author:      Thomas Scholz
 * Author URI:  http://wpkrauts.com/by/toscho/
 * Licence:     MIT
 * License URI: http://opensource.org/licenses/MIT
 */

/* Create an Autoloader here, do not put the class
 * declarations into the same file.
*/

new Controller( __FILE__ );

class Controller
{
	protected $plugin_file;

	public function __construct( $file )
	{
		$this->plugin_file = $file;

		\add_action( 'wp_loaded', array ( $this, 'plugin_setup' ) );
	}

	public function plugin_setup()
	{
		$data = new \stdClass;
		$this->set_plugin_data( $data );

		// class names
		$data->model   = __NAMESPACE__ . '\Plugin_Log_Data';
		$data->view    = __NAMESPACE__ . '\Console_Live_Logger';

		/* You can change the class names here, the new classes have just to
		 * implement the interfaces.
		* Or set "$data->stop" to TRUE in the filter to stop further processing.
		*/
		\apply_filters( 'wpkrauts_base_plugin_data', $data );

		if ( ! empty ( $data->stop ) )
			return;

		$model = new $data->model( $data );
		$view  = new $data->view( $model );

		\add_action( 'wp_print_footer_scripts', array ( $view, 'show' ) );

		\do_action( 'wpkrauts_base_plugin_loaded', $model, $view, $data );
	}

	protected function set_plugin_data( $data )
	{
		$data->url     = \plugins_url( '', $this->plugin_file );
		$data->dir     = \plugin_dir_path( $this->plugin_file );
		$plugin_info   = \get_file_data(
			$this->plugin_file,
			array (
				'name'    => 'Plugin Name',
				'version' => 'Version'
			)
		);
		$data->name    = $plugin_info['name'];
		$data->version = $plugin_info['version'];
	}
}

// Interfaces

/* These interfaces are not ideal, the constructor should not be defined here.
 * See http://wpkrauts.com/2013/how-to-build-flexible-php-interfaces/
*/

interface Log_Data
{
	public function __construct( \stdClass $data );

	public function get_value( $name );
}

interface Live_Logger
{
	public function __construct( Log_Data $model );

	public function show();
}

// Implementations

class Plugin_Log_Data implements Log_Data
{
	protected $data;

	public function __construct( \stdClass $data )
	{
		$this->data = $data;
	}

	public function get_value( $name )
	{
		if ( isset ( $this->data->$name ) )
			return $this->data->$name;

		return new \WP_Error(
			'1',
			"Invalid name: $name",
			debug_backtrace()
		);
	}
}

class Console_Live_Logger implements Live_Logger
{
	protected $model;

	public function __construct( Log_Data $model )
	{
		$this->model = $model;
	}

	public function show()
	{
		printf(
			'<script>console.log( \'%1$s\nVersion: %2$s\n%3$s\' );</script>',
			$this->get_js_var( 'name' ),
			$this->get_js_var( 'version' ),
			$this->get_js_var( 'bogus' ) // error demo
		);
	}

	protected function get_js_var( $var )
	{
		$text = $this->model->get_value( $var );

		if ( is_wp_error( $text ) )
			return "ERROR: " . esc_js( $text->get_error_message() );

		return esc_js( $text );
	}
}