<?php
/**
 * Plugin Name: Dspace-Plugin
 * Plugin URI: http://sedici.unlp.edu.ar/
 * Description: This plugin connects the repository SEDICI in wordpress, with the purpose of showing the publications of authors or institutions
 * Version: 1.0
 * Author: SEDICI - Ezequiel Manzur
 * Author URI: http://sedici.unlp.edu.ar/
 * Text Domain:   wp-dspace
 * Copyright (c) 2015 SEDICI UNLP, http://sedici.unlp.edu.ar
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 */

namespace Wp_dspace;
define( __NAMESPACE__ . '\DS', __NAMESPACE__ . '\\' );
define( DS . 'PLUGIN_NAME', 'wp-dspace' );
define( DS . 'PLUGIN_VERSION', '1.0.0' );
define( DS . 'PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );
define( DS . 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );
define( DS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( DS . 'PLUGIN_TEXT_DOMAIN', 'wp-dspace' );
/**
 * Autoload Classes
 */

require_once( PLUGIN_NAME_DIR . 'inc/libraries/autoloader_wp_dspace.php' );


require_once 'Shortcode.php';
require_once 'Dspace-config.php';
require_once 'configuration/config.php';
require_once 'util/class-widgetfilter.php';
require_once 'util/class-widgetvalidation.php';
require_once 'util/class-query.php';
require_once 'util/class-xmlorder.php';
require_once 'view/class-showshortcode.php';
require_once 'model/class-simplepiemodel.php';
require_once 'configuration/Configuration.php';
foreach ( glob ( "configuration/*_config.php" ) as $app ) {
    require_once $app;
}



//require_once 'class-dspace-widget.php';
add_action( 'widgets_init', function(){
	register_widget( 'Wp_dspace\Dspace_Widget' );
});

//add_action ( 'widgets_init', create_function ( '', 'return register_widget("Dspace");' ) );
print_r( add_shortcode ( 'get_publications', 'DspaceShortcode' ));





/**
 * Plugin Singleton Container
 */
class WP_Dspace {

	static $init;
	/**
	 * Loads the plugin
	 * @access    public
	 */
	public static function init() {

		if ( null == self::$init ) {
			self::$init = new Inc\Core\Init();
			self::$init->run();
		}

		return self::$init;
	}

}
/*
 * Comienza la ejecución del plugin
 */
function wp_dspace_init(){
	return WP_Dspace::init();
}

/**
 * Si se accede desde afuera de wordpress aborta la ejecución.
 */
if ( ! defined( 'WPINC' ) ) die;
wp_dspace_init();
