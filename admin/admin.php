<?php
/**
 * Administration functions for loading and displaying the settings page and saving settings 
 * are handled in this file.
 *
 * @package HelloBar
 */

/* Initialize the theme admin functionality. */
add_action( 'init', 'hello_bar_admin_init' );

/**
 * Initializes the theme administration functions.
 *
 * @since 0.8
 */
function hello_bar_admin_init() {
	add_action( 'admin_menu', 'hello_bar_settings_page_init' );

	add_action( 'hello_bar_update_settings_page', 'hello_bar_save_settings' );
	
	add_action( 'hello_bar_flush', 'hello_bar_flush_rewrite' );
	
	add_action( 'admin_init', 'hello_bar_admin_warnings' );
	
	add_action( 'admin_head', 'hello_bar_admin_css_fix' );
	
	add_action( 'admin_init', 'hello_bar_scripts' );
	
	add_action( 'admin_init', 'hello_bar_styles' );
}

/**
 * Register the javascript.
 *
 * @since 0.8
 */
function hello_bar_scripts() {
	$plugin_data = get_plugin_data( HELLO_BAR_DIR . 'hello-bar.php' );
	
	wp_register_script( 'hello-bar-admin', HELLO_BAR_JS . 'admin.js', array( 'jquery' ), $plugin_data['Version'], false );
	
	wp_register_script( 'jscolor', HELLO_BAR_JS . 'jscolor.js', false, '1.3.1', false );
}

/**
 * Register the stylesheets.
 *
 * @since 0.8
 */
function hello_bar_styles() {	
	$plugin_data = get_plugin_data( HELLO_BAR_DIR . 'hello-bar.php' );
	
	wp_register_style( 'hello-bar-tabs', HELLO_BAR_CSS . 'tabs.css', false, $plugin_data['Version'], 'screen' );
	
	wp_register_style( 'hello-bar-admin', HELLO_BAR_CSS . 'admin.css', false, $plugin_data['Version'], 'screen' );
}

/**
 * Sets up the cleaner gallery settings page and loads the appropriate functions when needed.
 *
 * @since 0.8
 */
function hello_bar_settings_page_init() {
	global $hello_bar;

	/* Create the theme settings page. */
	$hello_bar->settings_page = add_options_page( __( 'Hello Bar', 'hello-bar' ), __( 'Hello Bar', 'hello-bar' ), 6, 'hello-bar', 'hello_bar_settings_page' );

	/* Register the default theme settings meta boxes. */
	add_action( "load-{$hello_bar->settings_page}", 'hello_bar_create_settings_meta_boxes' );

	/* Make sure the settings are saved. */
	add_action( "load-{$hello_bar->settings_page}", 'hello_bar_load_settings_page' );

	/* Load the JavaScript and stylehsheets needed for the theme settings. */
	add_action( "load-{$hello_bar->settings_page}", 'hello_bar_settings_page_enqueue_script' );
	add_action( "load-{$hello_bar->settings_page}", 'hello_bar_settings_page_enqueue_style' );
	add_action( "admin_head-{$hello_bar->settings_page}", 'hello_bar_settings_page_load_scripts' );
}

/**
 * Returns an array with the default plugin settings.
 *
 * @since 0.8
 */
function hello_bar_settings() {
	$plugin_data = get_plugin_data( HELLO_BAR_DIR . 'hello-bar.php' );
	
	$settings = array(
		'version' => $plugin_data['Version'],
		'notice' => true,
		/* Activate */
		'activate' => false,		
		/* Rewrite Slug */	
		'slug' => 'announcing',
		
		/* Options */
		'height' => '33px',
		'background' => '#FFFFE0',
		'color' => '#444444',
		'a_color' => '#222222',
		'size' => '14px',
		'custom_css' => '',
	);
	return apply_filters( 'hello_bar_settings', $settings );
}

/**
 * Function run at load time of the settings page, which is useful for hooking save functions into.
 *
 * @since 0.8
 */
function hello_bar_load_settings_page() {

	/* Get theme settings from the database. */
	$settings = get_option( 'hello_bar_settings' );

	/* If no settings are available, add the default settings to the database. */
	if ( empty( $settings ) ) {
		add_option( 'hello_bar_settings', hello_bar_settings(), '', 'yes' );

		/* Redirect the page so that the settings are reflected on the settings page. */
		wp_redirect( admin_url( 'options-general.php?page=hello-bar' ) );
		exit;
	}

	/* If the form has been submitted, check the referer and execute available actions. */
	elseif ( isset( $_POST['hello-bar-settings-submit'] ) ) {

		/* Make sure the form is valid. */
		check_admin_referer( 'hello-bar-settings-page' );

		/* Available hook for saving settings. */
		do_action( 'hello_bar_update_settings_page' );
		
		/* Get the current theme settings. */
		$settings = get_option( 'hello_bar_settings' );
		
		if ( isset( $_POST['slug'] ) && $_POST['slug'] != $settings['slug'] )
			do_action( 'hello_bar_flush' );

		/* Redirect the page so that the new settings are reflected on the settings page. */
		wp_redirect( admin_url( 'options-general.php?page=hello-bar&updated=true' ) );
		exit;
	} 
	
	/* If the form has been submitted, check the referer and execute available actions. */
	elseif ( isset( $_GET['notice'] ) ) {

		/* Make sure the form is valid. */
		check_admin_referer( 'hello-bar-notice' );
		
		/* Get the current theme settings. */
		$settings = get_option( 'hello_bar_settings' );
		
		$settings['notice'] = ( ( isset( $_GET['notice'] ) ) ? false : true );

		/* Available hook for saving settings. */
		update_option( 'hello_bar_settings', $settings );

		/* Redirect the page so that the new settings are reflected on the settings page. */
		//wp_redirect( admin_url( 'options-general.php?page=hello-bar&updated=true' ) );
		//exit;
	}
}

/**
 * Validates the plugin settings.
 *
 * @since 0.8
 */
function hello_bar_save_settings() {

	/* Get the current theme settings. */
	$settings = get_option( 'hello_bar_settings' );

	$settings['version'] = esc_html( $_POST['version'] );
	$settings['activate'] = ( ( isset( $_POST['activate'] ) ) ? true : false );
	$settings['slug'] = esc_html( $_POST['slug'] );
	
	$settings['height'] = esc_html( $_POST['height'] );
	$settings['background'] = esc_html( $_POST['background'] );
	$settings['color'] = esc_html( $_POST['color'] );
	$settings['a_color'] = esc_html( $_POST['a_color'] );
	$settings['size'] = esc_html( $_POST['size'] );
	$settings['custom_css'] = esc_html( $_POST['custom_css'] );

	/* Update the theme settings. */
	$updated = update_option( 'hello_bar_settings', $settings );
}
	/**
	 * Save and rrewrite the rules
	 */
	function hello_bar_flush_rewrite() {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
	
/**
 * Registers the plugin meta boxes for use on the settings page.
 *
 * @since 0.8
 */
function hello_bar_create_settings_meta_boxes() {
	global $hello_bar;

	add_meta_box( 'hello-bar-activate-meta-box', __( 'Custom Slug Activation &mdash; <em>to infinity and beyond</em>', 'hello-bar' ), 'hello_bar_activate_meta_box', $hello_bar->settings_page, 'normal', 'high' );

	add_meta_box( 'hello-bar-announcement-meta-box', __( 'Hello!', 'hello-bar' ), 'hello_bar_announcement_meta_box', $hello_bar->settings_page, 'normal', 'high' );

	add_meta_box( 'hello-bar-general-meta-box', __( 'General Settings', 'hello-bar' ), 'hello_bar_general_meta_box', $hello_bar->settings_page, 'normal', 'high' );

	add_meta_box( 'hello-bar-about-meta-box', __( 'About Hello Bar', 'hello-bar' ), 'hello_bar_about_meta_box', $hello_bar->settings_page, 'advanced', 'high' );
	
	add_meta_box( 'hello-bar-support-meta-box', __( 'Support Hello Bar', 'hello-bar' ), 'hello_bar_support_meta_box', $hello_bar->settings_page, 'advanced', 'high' );
	
	add_meta_box( 'hello-bar-tabs-meta-box', __( 'TheFrosty Network', 'hello-bar' ), 'hello_bar_tabs_meta_box', $hello_bar->settings_page, 'side', 'low' );
}

/**
 * Displays activation meta box.
 *
 * @since 0.8
 */
function hello_bar_activate_meta_box() { 
	$num_posts = wp_count_posts( 'hellobar' );
	$num = number_format_i18n( $num_posts->publish );
	?>
	
    <?php if ( $num >= '1' ) { ?>
	<script type="text/javascript">
	jQuery(document).ready(
	function($) { 
		$('#hello-bar-activate-meta-box').css({'background-color':'#FFEBE8','border-color':'#CC0000'});
		$('h3.hndle em').append('<br /><small>You can not chage this after you publish one HB post.</small>');
	});
	</script><?php } ?>
	<table class="form-table">
		<tr>
			<th>
            	<label for="activate"><?php _e( 'Activate:', 'hello-bar' ); ?></label> 
            </th>
            <td>
            	<span id="slide">
				<input id="activate" name="activate" type="checkbox" <?php checked( hello_bar_get_setting( 'activate' ), true ); ?> value="true" />
                <label for="activate" class="check"></label>
                </span>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <div class="hide">Check this box to show or hide the announcement bar.</div>
            </td>
		</tr>
		<tr class="slug">
			<th>
            	<label for="slug"><?php _e( 'Slug:', 'hello-bar' ); ?></label> 
            </th>
            <td>
				<?php echo home_url( '/' ); ?><input id="slug" name="slug" type="input" value="<?php echo hello_bar_get_setting( 'slug' ); ?>" size="21" maxlength="21"<?php if ( $num >= '1' || ( hello_bar_get_setting( 'activate' ) == false && hello_bar_get_setting( 'slug' ) != '' ) ) echo ' readonly="readonly"'; ?> />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <div class="hide">Input your desired slug here.</div>
            </td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Display an announcement meta box.
 *
 * @since 0.8
 */
function hello_bar_announcement_meta_box() { ?>

	<iframe allowtransparency="true" src="" scrolling="no" style="height:50px;width:100%;">
	</iframe><!-- .form-table --><?php
}

/**
 * Displays the about meta box.
 *
 * @since 0.8
 */
function hello_bar_about_meta_box() {
	$plugin_data = get_plugin_data( HELLO_BAR_DIR . 'hello-bar.php' ); ?>

	<table class="form-table">
		<tr>
			<th><?php _e( 'Plugin:', 'hello-bar' ); ?></th>
			<td><?php echo $plugin_data['Title']; ?> <?php echo $plugin_data['Version']; ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Author:', 'hello-bar' ); ?></th>
			<td><?php echo $plugin_data['Author']; ?> &ndash; @<a href="http://twitter.com/TheFrosty" title="Follow me on Twitter">TheFrosty</a></td>
		</tr>
		<tr style="display: none;">
			<th><?php _e( 'Description:', 'hello-bar' ); ?></th>
			<td><?php echo $plugin_data['Description']; ?></td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.8
 */
function hello_bar_support_meta_box() { ?>

	<table class="form-table">
        <tr>
            <th><?php _e( 'Donate:', 'hello-bar' ); ?></th>
            <td><?php _e( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDD3EDC28RAWS">PayPal</a>.', 'hello-bar' ); ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Rate:', 'hello-bar' ); ?></th>
            <td><?php _e( '<a href="http://wordpress.org/extend/plugins/hello-bar/">This plugin on WordPress.org</a>.', 'hello-bar' ); ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Share:', 'hello-bar' ); ?></th>
            <td><?php _e( '<a href="http://twitter.com/home?status=Check+out+Hello+Bar+by+@TheFrosty+for+#WordPress!">On Twitter</a>', 'hello-bar' ); ?></td>
        </tr>
		<tr>
			<th><?php _e( 'Support:', 'hello-bar' ); ?></th>
			<td><?php _e( '<a href="http://wordpress.org/tags/hello-bar">WordPress support forums</a>.', 'hello-bar' ); ?></td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Displays the gallery settings meta box.
 *
 * @since 0.8
 */
function hello_bar_advanced_meta_box() { ?>

	<table class="form-table">
		<tr>
			<th>
            	<label for="custom_css"><?php _e( 'Custom CSS:', 'hello-bar' ); ?></label> 
            </th>
            <td>             
                <textarea id="custom_css" name="custom_css" cols="50" rows="3" class="large-text code"><?php echo wp_specialchars_decode( stripslashes( hello_bar_get_setting( 'custom_css' ) ), 1, 0, 1 ); ?></textarea>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide">Use this box to enter any custom CSS code that may not be shown below.<br />
                <strong>Example:</strong> <code>.login #backtoblog a { color:#990000; }</code><br />
                &sect; <strong>Example:</strong> <code>#snow { display:block; position:absolute; } #snow img { height:auto; width:100%; }</code><br />
                &sect; example CSS code for custom html code example..
                </span>
            </td>
   		</tr>
        
	</table><!-- .form-table --><?php
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//		login_form_border_top_color
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Displays the general meta box.
 *
 * @since 0.8
 */
function hello_bar_general_meta_box() { 
	$heights = array( 
		'' => '', 
		'28px' => '28px', 
		'33px' => '33px', 
		'40px' => '40px', 
	);
	$sizes = array( 
		'' => '', 
		'12px' => '12px', 
		'14px' => '14px', 
		'16px' => '16px', 
		'18px' => '18px', 
		'20px' => '20px', 
		'22px' => '22px', 
		'24px' => '24px', 
		'26px' => '26px', 
	);?>
	<table class="form-table">
        
        <tr style="display:none">
            <th>
            	<label for="height">bar height:</label> 
            </th>
            <td>
                <input id="height" name="height" value="<?php echo hello_bar_get_setting( 'height' ); ?>" size="10" maxlength="4" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide">Use two diget code with <code>px</code></span>
            </td>
   		</tr>
        
        <tr>
            <th>
            	<label for="height">bar height:</label> 
            </th>
            <td><select name="height" id="height" style="width:88px;">
					<?php foreach ( $heights as $option => $option_name ) { ?>
                        <option value="<?php echo $option; ?>" <?php selected( $option, hello_bar_get_setting( 'height' ) ); ?>><?php echo $option_name; ?></option>
                    <?php } ?>
                </select>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide">Select the bar size.</span>
            </td>
   		</tr>
        
        <tr>
        
            <th>
            	<label for="background">background color:</label> 
            </th>
            <td>
                <input class="color {hash:true,required:false,adjust:false}" id="background" name="background" value="<?php echo hello_bar_get_setting( 'background' ); ?>" size="10" maxlength="21" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide">Use HEX color <strong>with</strong> &ldquo;#&rdquo; <strong>or</strong> RGB/A format.<br />
				Example: &sup1;<code>#121212</code> &sup2;<code>rgba(255,255,255,0.4)</code>
                </span>
            </td>
   		</tr>
        
        <tr>
            <th>
            	<label for="size">font size:</label> 
            </th>
            <td><select name="size" id="size" style="width:88px;">
					<?php foreach ( $sizes as $option => $option_name ) { ?>
                        <option value="<?php echo $option; ?>" <?php selected( $option, hello_bar_get_setting( 'size' ) ); ?>><?php echo $option_name; ?></option>
                    <?php } ?>
                </select>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide">Select the font size.</span>
            </td>
   		</tr>
        
        <tr>
        
            <th>
            	<label for="color">text color:</label> 
            </th>
            <td>
                <input class="color {hash:true,required:false,adjust:false}" id="color" name="color" value="<?php echo hello_bar_get_setting( 'color' ); ?>" size="10" maxlength="21" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide">Use HEX color <strong>with</strong> &ldquo;#&rdquo; <strong>or</strong> RGB/A format.<br />
				Example: &sup1;<code>#121212</code> &sup2;<code>rgba(255,255,255,0.4)</code>
                </span>
            </td>
   		</tr>
        
        <tr>
        
            <th>
            	<label for="a_color">anchor color:</label> 
            </th>
            <td>
                <input class="color {hash:true,required:false,adjust:false}" id="a_color" name="a_color" value="<?php echo hello_bar_get_setting( 'a_color' ); ?>" size="10" maxlength="21" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide">Use HEX color <strong>with</strong> &ldquo;#&rdquo; <strong>or</strong> RGB/A format.<br />
				Example: &sup1;<code>#121212</code> &sup2;<code>rgba(255,255,255,0.4)</code>
                </span>
            </td>
   		</tr>
        
	</table><!-- .form-table --><?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.8
 */
function hello_bar_tabs_meta_box() { ?>
	<table class="form-table">
        <div id="tab" class="tabbed inside">
    	
        <ul class="tabs">        
            <li class="t1 t"><a class="t1 tab">Austin Passy</a></li>
            <li class="t2 t"><a class="t2 tab">WordCampLA</a></li>
            <li class="t3 t"><a class="t3 tab">wpWorkShop</a></li>  
            <li class="t4 t"><a class="t4 tab"><em>WP</em>Wag</a></li> 
            <li class="t5 t"><a class="t5 tab">Float-O-holics</a></li>  
            <li class="t6 t"><a class="t6 tab">Great Escape</a></li>   
            <li class="t7 t"><a class="t7 tab">PDXbyPix</a></li>             
        </ul>
        
		<?php 
		if ( function_exists( 'thefrosty_network_feed' ) ) {
        	thefrosty_network_feed( 'http://austinpassy.com/feed', '1' );
			thefrosty_network_feed( 'http://2010.wordcamp.la/feed', '2' );
       		thefrosty_network_feed( 'http://wpworkshop.la/feed', '3' );
        	thefrosty_network_feed( 'http://wpwag.com/feed', '4' ); 
        	thefrosty_network_feed( 'http://floatoholics.com/feed', '5' );
        	thefrosty_network_feed( 'http://greatescapecabofishing.com/feed', '6' ); 
        	thefrosty_network_feed( 'http://pdxbypix.com/feed', '7' );  
		} ?>
        
    	</div>
	</table><!-- .form-table --><?php
}

/**
 * Displays a settings saved message.
 *
 * @since 0.8
 */
function hello_bar_settings_update_message() { ?>
	<div class="updated fade">
		<p><strong><?php _e( 'Don&prime;t you feel good. You just saved me.', 'hello-bar' ); ?></strong></p>
	</div><?php
}

/**
 * Outputs the HTML and calls the meta boxes for the settings page.
 *
 * @since 0.8
 */
function hello_bar_settings_page() {
	global $hello_bar;

	$plugin_data = get_plugin_data( HELLO_BAR_DIR . 'hello-bar.php' ); ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Hello Bar Settings', 'hello-bar' ); ?></h2>

		<?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) hello_bar_settings_update_message(); ?>

		<div id="poststuff">

			<form method="post" action="<?php admin_url( 'options-general.php?page=hello-bar' ); ?>">

				<?php wp_nonce_field( 'hello-bar-settings-page' ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

				<div class="metabox-holder">
					<div class="post-box-container column-1 normal"><?php do_meta_boxes( $hello_bar->settings_page, 'normal', $plugin_data ); ?></div>
					<div class="post-box-container column-2 advanced"><?php do_meta_boxes( $hello_bar->settings_page, 'advanced', $plugin_data ); ?></div>
					<div class="post-box-container column-3 side" style="clear:both;"><?php do_meta_boxes( $hello_bar->settings_page, 'side', $plugin_data ); ?></div>
				</div>

				<p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="<?php _e( 'Update Settings', 'hello-bar' ); ?>" />
					<input type="hidden" name="hello-bar-settings-submit" value="true" />
				</p><!-- .submit -->

			</form>

		</div><!-- #poststuff -->

	</div><!-- .wrap --><?php
}

/**
 * Loads the scripts needed for the settings page.
 *
 * @since 0.8
 */
function hello_bar_settings_page_enqueue_script() {	
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'wp-lists' );
	wp_enqueue_script( 'postbox' );
	//wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'hello-bar-admin' );
	wp_enqueue_script( 'jscolor' );
}

/**
 * Loads the stylesheets needed for the settings page.
 *
 * @since 0.8
 */
function hello_bar_settings_page_enqueue_style() {
	//wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'hello-bar-tabs' );
	wp_enqueue_style( 'hello-bar-admin' );
}

/**
 * Loads the metabox toggle JavaScript in the settings page head.
 *
 * @since 0.8
 */
function hello_bar_settings_page_load_scripts() {
	global $hello_bar; ?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			postboxes.add_postbox_toggles( '<?php echo $hello_bar->settings_page; ?>' );
		});
		//]]>
	</script><?php
}

function hello_bar_admin_css_fix() { ?>
<style type="text/css">
#menu-posts-hellobar .wp-menu-image a {overflow:hidden}
#menu-posts-hellobar .wp-menu-image img {position: relative;top:-24px}
#menu-posts-hellobar.wp-has-current-submenu .wp-menu-image img {top:0}
</style>
<?php }

/**
 * Plugin Action /Settings on plugins page
 * @since 0.4.2
 * @package plugin
 */
function hello_bar_plugin_actions( $links, $file ) {
 	if( $file == 'hello-bar/hello-bar.php' && function_exists( "admin_url" ) ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=hello-bar' ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

/**
 * Warnings
 * @since 0.5
 * @package admin
 */
function hello_bar_admin_warnings() {
	global $hello_bar;
		
		function hello_bar_warning_slug() {
			global $hello_bar;
			$num_posts = wp_count_posts( 'hellobar' );
			$num = number_format_i18n( $num_posts->publish );
			$slug = hello_bar_get_setting( 'slug' );

			if ( hello_bar_get_setting( 'notice' ) == true ) { ?>
                <div id="hello-bar-warning" class="updated">
                    <p><?php _e( sprintf( 'You can set up a custom slug for <strong>Hello Bar</strong>, just visit the %1$s page before adding a post to the %2$s.<br />Current slug set as <code>%3$s</code> %4$s', '<a href="' . admin_url( 'options-general.php?page=hello-bar' ) . '">options</a>', '<a href="' . admin_url( 'edit.php?post_type=hellobar' ) . '">hello bar</a>', home_url( '/' ).'<big><strong>'.$slug.'</strong></big>/', '<a href="' . admin_url( 'options-general.php?page=hello-bar&notice=false&_wpnonce=' . wp_create_nonce( 'hello-bar-notice' ) ) . '" class="right alignright">hide</a>' ), 'hello-bar' ); ?>
                    </p>
                </div><?php 
			}
		}
		
		function hello_bar_warning_version() {
			global $hello_bar, $wp_version; ?>
                <div id="hello-bar-warning" class="error">
                    <p><?php _e( sprintf( '<strong>Hello Bar</strong>, will inly work with WordPress 3.0.x and greater. You\'ve got version %1$s', $wp_version ), 'hello-bar' ); ?>
                    </p>
                </div><?php
		}
		
		if ( is_version() )
			add_action( 'admin_notices', 'hello_bar_warning_slug' );
		else
			add_action( 'admin_notices', 'hello_bar_warning_version' );

	return;
}

/**
 * RSS Feed
 * @since 0.3
 * @package Admin
 */
if ( !function_exists( 'thefrosty_network_feed' ) ) {
	function thefrosty_network_feed( $attr, $count ) {		
		global $wpdb;
		
		include_once( ABSPATH . WPINC . '/rss.php' );		
		$rss = fetch_rss( $attr );		
		$items = array_slice( $rss->items, 0, '3' );
		//for( $i = 0; $i < 3; $i++ ) { 
			//$item = $rss->items[$i];
			echo '<div class="t' . $count . ' tab-content postbox open feed">';		
			echo '<ul>';		
			if ( empty( $items ) ) 
				echo '<li>No items</li>';		
			else		
			foreach ( $items as $item ) : ?>		
                <li>		
                	<a href='<?php echo $item[ 'link' ]; ?>' title='<?php echo $item[ 'description' ]; ?>'><?php echo $item[ 'title' ]; ?></a><br /> 		
                	<span style="font-size:10px; color:#aaa;"><?php echo date( 'F, j Y', strtotime( $item[ 'pubdate' ] ) ); ?></span>		
                </li>		
			<?php endforeach;		
			echo '</ul>';		
			echo '</div>';	
		//}
	}
}

?>