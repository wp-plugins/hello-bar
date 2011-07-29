<?php
/**
 * Plugin Name: Hello Bar (Update to Announcement Bar)
 * Plugin URI: http://austinpassy.com/wordpress-plugins/announcement-bar
 * Description: Please delete and install "announcement bar" instead.
 * Version: 0.09
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
	add_action( 'admin_init', 'hello_bar_admin_warnings' );
}

function hello_bar_admin_warnings() {
	global $hello_bar;
		
		function hello_bar_change() {
			global $hello_bar; 
			
			$hellos_bar = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . 'announcement-bar/announcement-bar.php' );
			
			if ( empty( $hellos_bar ) || $hellos_bar['Name'] != 'Announcement Bar' ) { ?>
            
            <div id="hello-bar-warning" class="error">
                <p><?php
				if ( is_multisite() ) 
					_e( sprintf( 
					'You need to install <em>Announcement Bar</em> as this plugin is deprecated. <a href="%1$s" title="Hellos!">Install</a> it now.', 
					admin_url( 'network/plugin-install.php' ), 'hello-bar' ) );
				else 
					_e( sprintf( 
					'You need to install <em>Announcement Bar</em> as this plugin is deprecated. <a href="%1$s" title="Hellos!">Install</a> it now.',
					admin_url( 'plugin-install.php?tab=search&type=term&s=announcement+bar&plugin-search-input=Search+Plugins' ), 'hello-bar' ) );
				?></p>
            </div><?php
			}
		}
		
		add_action( 'admin_notices', 'hello_bar_change' );

	return;
}

?>