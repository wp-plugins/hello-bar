<?php
/**
 * Administration functions for loading and displaying the settings page and saving settings 
 * are handled in this file.
 *
 * @package HelloBar
 */

/**
 * Initializes the theme administration functions.
 *
 * @since 0.8
 */
function hello_bar_cpt_init() {
	/* Register post_types & multiple templates */
	add_action( 'init', 'hello_register_post_type' );
	
	/* Save the testimonial meetadata */	
	add_action( 'save_post', 'save_hello_meta_box', 10, 2 );
	
	add_action( 'template_redirect', 'hello_bar_count_and_redirect' ) ;
	
	/* Column manager for testimonials */
	add_filter( 'manage_posts_columns', 'hello_columns', 10, 2 );
	add_action( 'manage_posts_custom_column', 'hello_column_data', 10, 2 );
}

/**
 * Fire this during init
 * @ref http://wordpress.pastebin.com/VCeaJBt8
 * Thanks to @_mfields
 */
function hello_register_post_type() {
	global $hello_bar;
	
	//$rewrite = array( 'slug' => 'announcing', 'with_front' => false );
	
	$slug = sanitize_title_with_dashes( hello_bar_get_setting( 'slug' ) );
	
	if ( ! empty( $slug ) ) {
		$rewrite['slug'] = sanitize_title_with_dashes( hello_bar_get_setting( 'slug' ) );
	}
	
	/* Labels for the hellos post type. */
	$hello_labels = array(
		'name' => __( 'Hello Bar', 'hello-bar' ),
		'singular_name' => __( 'Hello Bar', 'hello-bar' ),
		'add_new' => __( 'Add New', 'hello-bar' ),
		'add_new_item' => __( 'Add New Hello Bar post', 'hello-bar' ),
		'edit' => __( 'Edit', 'hello-bar' ),
		'edit_item' => __( 'Edit a Hello Bar post', 'hello-bar' ),
		'new_item' => __( 'New Hello Bar post', 'hello-bar' ),
		'view' => __( 'View Hello Bars', 'hello-bar' ),
		'view_item' => __( 'View Hello Bar post', 'hello-bar' ),
		'search_items' => __( 'Search Hello Bar posts', 'hello-bar' ),
		'not_found' => __( 'No hello bar posts found', 'hello-bar' ),
		'not_found_in_trash' => __( 'No hello bar posts found in Trash', 'hello-bar' ),
	);

	/* Arguments for the hellos post type. */
	$hello_args = array(
		'labels' => $hello_labels,
		'has_archive' => 'false',
		'capability_type' => 'post',
		'public' => true,
		'can_export' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
		'menu_icon' => plugin_dir_url( __FILE__ ) . '/hello.png',
		'supports' => array( 'title',  'entry-views' ),
		'register_meta_box_cb' => 'hello_bar_metaboxes',
	);

	/* Register the hellos post type. */
	register_post_type( 'hellobar', $hello_args );
}

/**
 * Register the metaboxes
 */
function hello_bar_metaboxes() {	
	add_meta_box( 'hello-meta-box', __( 'Hello', 'hello-bar' ), 'hello_metabox_settings', 'hellobar', 'normal', 'default');
}

/**
 * The hello metabox
 */
function hello_metabox_settings() {
	global $post; 
	
	// Noncename needed to verify where the data originated
	wp_nonce_field( 'hello', 'hello-nonce', false );
	
	$hello = get_post_meta( $post->ID, '_hello_content', true );
	$count = get_post_meta( $post->ID, '_hello_count', true );
	$link = get_post_meta( $post->ID, '_hello_link', true ); ?>
	
	<table class="form-table">
    	<tr>
            <td style="width:10%;vertical-align:top"><label for="content">Content:</label></td>
            <td colspan="3"><textarea name="content" id="content" rows="4" cols="80" tabindex="30" style="width:97%"><?php echo esc_html( $hello ); ?></textarea><br />
            <span style="color:#bbb">Please enter your plain text content here. <strong>(No HTML allowed)</strong></span></td>
        </tr>
    	<tr>
            <td style="width:10%;vertical-align:top"><label for="cite">Link:</label></td>
            <td>
            	<input type="text" name="link" id="link" value="<?php echo esc_html( $link ); ?>" size="30" tabindex="30" style="width:90%" /><br />
				<?php $counter = isset( $post->ID ) ? $count : 0;
				printf( '<span style="color:#bbb">This URL has been accessed <strong>%d</strong> times.</span>', esc_attr( $counter ) ); ?>
            </td>
        </tr>
	</table><!-- .form-table --><?php
}

/**
 * Save the metabox aata
 */
function save_hello_meta_box( $post_id, $post ) {
	global $post_type, $post;
		
	/* Make sure the form is valid. */
	$nonce = $_REQUEST['hello-nonce'];
	if ( !wp_verify_nonce( $nonce, 'hello' ) ) {
		return $post->ID;
	}
	
	// Is the user registered as a subscriber.
	if ( !current_user_can( 'manage_links', $post->ID ) )
		return $post->ID;
		
	$meta['_hello_content'] = esc_html( $_POST['content'] );
	//$meta['_hello_count'] = esc_html( $_POST['count'] );
	$meta['_hello_link'] = esc_html( $_POST['link'] );
	
	foreach ($meta as $key => $value) {
		if( $post->post_type == 'revision' )
			return;
		$value = implode(',', (array)$value);
		if(get_post_meta($post->ID, $key, FALSE)) {
			update_post_meta($post->ID, $key, $value);
		} else {
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key);
	}
}

function hello_bar_count_and_redirect() {
		
	if ( !is_singular('hellobar') )
		return;

	global $wp_query;
	
	// Update the count
	$count = isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_hello_count', true ) : 0;
	update_post_meta( $wp_query->post->ID, '_hello_count', $count + 1 );

	// Handle the redirect
	$redirect = isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_hello_link', true ) : '';

	if ( !empty( $redirect ) ) {
		wp_redirect( esc_url_raw( $redirect ), 301);
		exit;
	}
	else {
		wp_redirect( home_url(), 302 );
		exit;
	}
	
}

function hello_columns( $columns, $post_type ) {
	global $post_type, $post;
		
	if ( 'hellobar' == $post_type ) :
		$columns = array(
			'cb'			=> '<input type="checkbox" />',
			'email'			=> 'Author',
			'title'			=> 'Title', //So an edit link shows. :P
			'link'			=> 'Link',
			'count'			=> 'Hits',
			'date'			=> 'Date'
		);	
	endif;
		
	return $columns;
}

function hello_column_data( $column_name, $post_id ) {
	global $post_type, $post, $user;
	
	if( 'hellobar' == $post_type ) :	
		if( 'email' == $column_name ) :
			$email =  get_the_author_meta( $user_email, $userID );
			$default = '';
			$size = 40;
			$gravatar = 'http://www.gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) ) . '?d=' . $default . '&s=' . $size;
			echo '<img alt="" src="'.$gravatar.'" />';
		elseif( 'link' == $column_name ) :
			$perm = get_permalink( $post->ID );
			$url = get_post_meta( $post->ID, '_hello_link', true );		
			//echo make_clickable( esc_url( $perm ? $perm : '' ) );
			echo '<a href="'.$perm.'">'.esc_url( $url ? $url : $perm ).'</a>';
		elseif( 'count' == $column_name ) :
			$count = get_post_meta( $post->ID, '_hello_count', true );
			echo esc_html( $count ? $count : 0 );
		endif;
	endif;
}

?>