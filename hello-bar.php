<?php
/**
 * Plugin Name: Hello Bar (Update to Hellos Bar)
 * Plugin URI: http://austinpassy.com/wordpress-plugins/hellos-bar
 * Description: Please delete and install hellos bar instead.
 * Version: 0.07
 * Author: Austin Passy
 * Author URI: http://frostywebdesigns.com
 *
 * @copyright 2009 - 2011
 * @author Austin Passy
 * @link http://frostywebdesigns.com/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package HelloBar
 */

/* Set up the plugin. */
add_action( 'plugins_loaded', 'hello_bar_setup' );
	

/**
 * Sets up the Hello Bar plugin and loads files at the appropriate time.
 *
 * @since 0.8
 */
function hello_bar_setup() {

	/* Set constant path to the Cleaner Gallery plugin directory. */
	define( 'HELLO_BAR_DIR', plugin_dir_path( __FILE__ ) );
	define( 'HELLO_BAR_ADMIN', HELLO_BAR_DIR . '/admin/' );

	/* Set constant path to the Cleaner Gallery plugin URL. */
	define( 'HELLO_BAR_URL', plugin_dir_url( __FILE__ ) );
	define( 'HELLO_BAR_CSS', HELLO_BAR_URL . 'css/' );
	define( 'HELLO_BAR_JS', HELLO_BAR_URL . 'js/' );
	
	add_action( 'admin_init', 'hello_bar_admin_warnings' );

	do_action( 'hello_bar_loaded' );
}

function hello_bar_admin_warnings() {
	global $hello_bar;
		
		function hello_bar_change() {
			global $hello_bar; ?>
                <div id="hello-bar-warning" class="error">
                    <p><?php _e( '<strong>Hello Bar</strong> needs to be updated to <em>Hello<strong>s</strong> Bar</em>, in the WordPress repository.', 'hello-bar' ); ?></p>
                </div><?php
		}
		
		add_action( 'admin_notices', 'hello_bar_change' );

	return;
}

?>