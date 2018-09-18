<?php
namespace Wp_dspace\Inc\Core;
use Wp_dspace as DS;
use Wp_dspace\Inc\Admin as Admin;
use Wp_dspace\Inc\Frontend as Frontend;


/**
 * Clase para administar el plugin, los hook, internacionalizacion.
 *
 * @author Sedici-Manzur Ezequiel
 */
class Init {

	/**
	 *
	 * @var      Loader    $loader    es el encargado de mantener y administar los hooks.
	 */
	protected $loader;
	/**

	 * @var      string    $plugin_base_name    string para identificar al plugin
	 */
	protected $plugin_basename;
	/**
	 * @var      string    $version   Version actual del plugin.
	 */
	protected $version;
	/**
	 * @var      string    $plugin_text_domain    Text domain del plugin.
	 */
	protected $plugin_text_domain;
	//Define la funcionalidad del plugin
	public function __construct() {

		$this->plugin_name = DS\PLUGIN_NAME;
		$this->version = DS\PLUGIN_VERSION;
		$this->plugin_basename = DS\PLUGIN_BASENAME;
		$this->plugin_text_domain = DS\PLUGIN_TEXT_DOMAIN;

		$this->load_dependencies();
		//$this->set_locale();
		$this->define_admin_hooks();
		//$this->define_public_hooks();
	}
	/**
	 *
	 * - Loader - Administra  los hooks del plugin.
	 * - Internationalization_i18n - Define la funcionalidad de internacionalización.
	 * - Admin - Define todos los hooks de admin.
	 * - Frontend - Defines all hooks for the public side of the site.
	 *
	 * @access    private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();

	}
	/**
	 * Defina la configuración regional del plugin.
	 *
	 * @access    private
	 */
	private function set_locale() {

		$plugin_i18n = new Internationalization_i18n( $this->plugin_text_domain );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * Callbacks are documented in inc/admin/class-admin.php
	 *
	 * @access    private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin\Admin( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
		//Registro de estilos y scripts
		$this->loader->add_action('init',$plugin_admin,'register_styles');
		$this->loader->add_action('init',$plugin_admin,'register_scripts');
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//Add a top-level admin menu for our plugin
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		//when a form is submitted to admin-post.php
		$this->loader->add_action( 'admin_post_form_config', $plugin_admin, 'the_form_response');


		// Register admin notices
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'print_plugin_admin_notices');

	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access    private
	 */
	private function define_public_hooks() {

		$plugin_public = new Frontend\Frontend( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_basename;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the text domain of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The text domain of the plugin.
	 */
	public function get_plugin_text_domain() {
		return $this->plugin_text_domain;
	}

}