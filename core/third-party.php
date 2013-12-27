<?php
/**
 * Main class for third-party
 *
 * Define properties & methods
 *
 * @author		InnoThemes Team <support@innothemes.com>
 * @package		IGPGBLDR
 * @version		$Id$
 */
class Ig_Pb_Third_Party {

	// prodiver name
	protected $provider;
	// register assets (js/css)
	protected $assets_register;
	// enqueue assets for Admin pages
	protected $assets_enqueue_admin;
	// enqueue assets for Modal setting iframe
	protected $assets_enqueue_modal;
	// enqueue assets for Frontend
	protected $assets_enqueue_frontend;

	// GET functions
	public function get_provider(){
		return $this->provider;
	}
	public function get_assets_register(){
		return $this->assets_register;
	}
	public function get_assets_enqueue_admin(){
		return $this->assets_enqueue_admin;
	}
	public function get_assets_enqueue_modal(){
		return $this->assets_enqueue_modal;
	}
	public function get_assets_enqueue_frontend(){
		return $this->assets_enqueue_frontend;
	}

	// SET FUNCTIONS
	/**
	 *
	 * @param array $provider
	 * array(
			'path' => THIS_PATH,
			'name' => THIS_NAME,
			'shortcode_dir' => THIS_PATH . 'shortcodes',
			'js_shortcode_dir' => array(
				'path' => THIS_PATH . 'shortcodes_js',
				'uri' => THIS_URI . 'shortcodes_js',
			),
		)
	 */
	public function set_provider( $provider ){
		$this->provider = $provider;
	}
	/**
	 *
	 * @param array $assets
	 */
	public function set_assets_register( $assets ){
		$this->assets_register = $assets;
	}
	/**
	 *
	 * @param array $assets
	 */
	public function set_assets_enqueue_admin( $assets ){
		$this->assets_enqueue_admin = $assets;
	}
	/**
	 *
	 * @param array $assets
	 */
	public function set_assets_enqueue_modal( $assets ){
		$this->assets_enqueue_modal = $assets;
	}
	/**
	 *
	 * @param array $assets
	 */
	public function set_assets_enqueue_frontend( $assets ){
		$this->assets_enqueue_frontend = $assets;
	}

	// constructor
	public function __construct() {
		add_filter( 'ig_pb_provider', array( &$this, 'this_provider' ) );
		add_filter( 'ig_pb_assets_register', array( &$this, 'this_assets_register' ) );
		add_filter( 'ig_pb_assets_enqueue_admin', array( &$this, 'this_assets_enqueue_admin' ) );
		add_filter( 'ig_pb_assets_enqueue_modal', array( &$this, 'this_assets_enqueue_modal' ) );
		add_filter( 'ig_pb_assets_enqueue_frontend', array( &$this, 'this_assets_enqueue_frontend' ) );
	}

	// filter providers
	public function this_provider( $providers ){
		$provider = $this->get_provider();
		if ( empty ( $provider ) || empty ( $provider['path'] ) ){
			return $providers;
		}
		$path = $provider['path'];
		$uri = $provider['uri'];
		$shortcode_dir    = empty ( $provider['shortcode_dir'] ) ? 'shortcodes' : $provider['shortcode_dir'];
		$js_shortcode_dir = empty ( $provider['js_shortcode_dir'] ) ? 'assets/js/shortcodes' : $provider['js_shortcode_dir'];

		$providers[$path] = array(
			'name' => $provider['name'],
			'shortcode_dir' => array( $path . $shortcode_dir ),
			'js_shortcode_dir' => array( 'path' => $path . $js_shortcode_dir, 'uri' => $uri . $js_shortcode_dir ),
		);
		return $providers;
	}
	// register assets
	public function this_assets_register( $assets ){
		$assets = array_merge( $assets, $this->get_assets_register() );
		return $assets;
	}
	// assets enqueue for admin
	public function this_assets_enqueue_admin( $assets ){
		$assets = array_merge( $assets, $this->get_assets_enqueue_admin() );
		return $assets;
	}
	// assets enqueue for modal
	public function this_assets_enqueue_modal( $assets ){
		$assets = array_merge( $assets, $this->get_assets_enqueue_modal() );
		return $assets;
	}
	// assets enqueue for frontend
	public function this_assets_enqueue_frontend( $assets ){
		$assets = array_merge( $assets, $this->get_assets_enqueue_frontend() );
		return $assets;
	}

	/**
	 * Register Path to extended Parameter type
	 * @param string $path
	 */
	public function register_extended_parameter_path( $path ) {
		IG_Pb_Loader::register( $path, 'IG_Pb_Helper_Html_' );
	}

}