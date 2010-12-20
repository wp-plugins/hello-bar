<?php
/**
 * Plugin Name: Hello Bar
 * Plugin URI: http://austinpassy.com/wordpress-plugins/hello-bar
 * Description: A fixed position (header) jQuery pop-up announcemnet bar. (currently &alpha; testing)
 * Version: 0.03
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
add_action( 'plugins_loaded', 'hello_bar_cpt_init' );
	

/**
 * Sets up the Hello Bar plugin and loads files at the appropriate time.
 *
 * @since 0.8
 */
function hello_bar_setup() {
	/* Load translations. */
	load_plugin_textdomain( 'hello-bar', false, 'hello-bar/languages' );

	/* Set constant path to the Cleaner Gallery plugin directory. */
	define( 'HELLO_BAR_DIR', plugin_dir_path( __FILE__ ) );
	define( 'HELLO_BAR_ADMIN', HELLO_BAR_DIR . '/admin/' );

	/* Set constant path to the Cleaner Gallery plugin URL. */
	define( 'HELLO_BAR_URL', plugin_dir_url( __FILE__ ) );
	define( 'HELLO_BAR_CSS', HELLO_BAR_URL . 'css/' );
	define( 'HELLO_BAR_JS', HELLO_BAR_URL . 'js/' );

	if ( is_admin() )
		require_once( HELLO_BAR_ADMIN . 'admin.php' );
		require_once( HELLO_BAR_ADMIN . 'post-type.php' );
	
	/* Add a settings page to the plugin menu */
	add_filter( 'plugin_action_links', 'hello_bar_plugin_actions', 10, 2 );
	
	add_filter( 'show_admin_bar', '__return_false' );
	
	/* Print script */
	add_action( 'wp_print_scripts', 'hello_bar_script' );
	
	/* Print style */
	add_action( 'wp_print_styles', 'hello_bar_style' );
	
	/* Add HTML */
	add_action( 'wp_footer', 'hello_bar_html', 999 );

	do_action( 'hello_bar_loaded' );
}

/**
 * Function for quickly grabbing settings for the plugin without having to call get_option() 
 * every time we need a setting.
 *
 * @since 0.01
 */
function hello_bar_get_setting( $option = '' ) {
	global $hello_bar;

	if ( !$option )
		return false;

	if ( !isset( $hello_bar->settings ) )
		$hello_bar->settings = get_option( 'hello_bar_settings' );

	return $hello_bar->settings[$option];
}

/**
 * WordPress 3.x check
 *
 * @since 0.01
 */
if ( ! function_exists( 'is_version' ) ) {
	function is_version( $version = '3.0' ) {
		global $wp_version;
		
		if ( version_compare( $wp_version, $version, '<' ) ) {
			return false;
		}
		return true;
	}
}

/**
 * Add script
 * @since 0.01
 */
function hello_bar_script() {
	global $hello_bar;
	
	if ( hello_bar_get_setting( 'activate' ) == true )
	
	wp_enqueue_script( 'hello-bar', HELLO_BAR_JS . 'hello.js', array( 'jquery' ), '0.1', true );
}

/**
 * Add stylesheet
 * @since 0.01
 */
function hello_bar_style() {
	global $hello_bar;
	
	if ( hello_bar_get_setting( 'activate' ) == true )
	
	wp_enqueue_style( 'hello-bar', HELLO_BAR_CSS . 'hello.css.php', false, 0.1, 'screen' );
}

/**
 * Add the HTML
 */
function hello_bar_html() {
	global $post, $hello_bar;
	
	if ( hello_bar_get_setting( 'activate' ) == true ) {
	
	query_posts( array( 'post_type' => 'hellobar', 'posts_per_page' => '1', 'orderby' => 'rand' ) ); ?>
    
    <div id="hello-bar-container" class="no-js">
        
        <div class="tab">
            <div class="toggle">
                <a class="open" title="<?php _e('Show panel', 'hello-bar'); ?>"><?php _e('<span class="arrow">&darr;</span>', 'hello-bar'); ?></a>
                <a class="close" title="<?php _e('Hide panel', 'hello-bar'); ?>" style="display: none;"><?php _e('<span class="arrow">&uarr;</span>', 'hello-bar'); ?></a>
            </div><!-- /.toggle -->
        </div><!-- /.tab -->
    
        <div id="hello-bar" class="no-js">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
				$content = get_post_meta( $post->ID, '_hello_content', true );
				$link = get_post_meta( $post->ID, '_hello_link', true );
				$plink = get_permalink( $post->ID );
			?>				
			<div id="hello-<?php the_ID(); ?>" class="hello">
            	<p><?php echo wp_specialchars_decode( stripslashes( $content ), 1, 0, 1 ); 
					if ( $link ) { ?>
                <a href="<?php echo $plink; ?>"><?php echo $link; ?></a><?php } //make_clickable( esc_url( $link ? $plink : $link ) ); ?></p>
            </div>
            <?php endwhile; endif; ?>
            
            <div class="branding">
                <a class="branding" href="http://austinpassy.com/wordpress-plugin/hello-bar" title="Plugin by Austin &ldquo;Frosty&rdquo; Passy">&#9731;</a>
            </div><!-- /.branding -->
            
        </div><!-- /#hello-bar -->
        
	</div><!-- /#hello-bar-container -->
    
<?php } }

?>