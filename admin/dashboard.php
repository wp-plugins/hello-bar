<?php
/** 
 * Function: Register Dashboard Widget
 */
	add_action( 'wp_dashboard_setup', 'cl_register_dashboard_widget' );

if ( !function_exists( 'cl_register_dashboard_widget' ) ) :
function cl_register_dashboard_widget() {
	wp_register_sidebar_widget('cl_dashboard', __('The Frosty Network <em>feeds</em>'), 'cl_dashboard',
		array(
		'all_link' => 'http://thefrosty.net/',
		'feed_link' => 'http://pipes.yahoo.com/pipes/pipe.run?_id=52c339c010550750e3e64d478b1c96ea&_render=rss',
		'width' => 'half', // OR 'fourth', 'third', 'half', 'full' (Default: 'half')
		'height' => 'double', // OR 'single', 'double' (Default: 'single')
		)
	);
}
endif;


// Function: Add Dashboard Widget
	add_filter( 'wp_dashboard_widgets', 'cl_add_dashboard_widget' );
	
if ( !function_exists( 'cl_add_dashboard_widget' ) ) :
function cl_add_dashboard_widget($widgets) {
	global $wp_registered_widgets;
	if (!isset($wp_registered_widgets['cl_dashboard'])) {
		return $widgets;
	}
	array_splice($widgets, sizeof($widgets)-1, 0, 'cl_dashboard');
	return $widgets;
}
endif;


// Function: Print Dashboard Widget
if ( !function_exists( 'cl_dashboard' ) ) :
function cl_dashboard($sidebar_args) {
	global $wpdb;
	extract( array($sidebar_args, EXTR_SKIP));
	echo $before_widget;
	echo $before_title;
	echo $widget_name;
	echo $after_title;
	//echo '<a href="http://wpcult.com/"><img style="float:right; margin: 0 0 5px 5px;" src="http://wpcult.com/cult-logo-rss.png" alt="WPCult"/></a>';
			
		include_once(ABSPATH . WPINC . '/rss.php');
			
		$rss = fetch_rss('http://pipes.yahoo.com/pipes/pipe.run?_id=52c339c010550750e3e64d478b1c96ea&_render=rss');
		// See: http://alexpolski.com/2009/03/25/how-to-merge-multiple-feeds-to-one-feed/
		//$items = array_slice($rss->items, 0, 6);
		for( $i = 0; $i < 6; $i++ ) {
		if (empty($items)) echo '<p>Nothing to see people..</p>';
		
		else
		
		$itemcount == 0;
		
		foreach ( $items as $item ) : $itemcount++; ?>
        
			<p>
            	<a style="font-size: 14px;" href='<?php echo $item['link']; ?>' title='<?php echo $item['description']; ?>'><?php echo $item['title']; ?></a>
            		<br />
				<span style="font-size: 10px; color: #aaa;"><?php echo date('F, j Y',strtotime($item['pubdate'])); ?></span>
			</p>
            
		<?php if ( $itemcount == 2 ) {
			echo '<script type="text/javascript" src="http://wpads.net/ads/js-rss.php?type=link&align=center&zone=1"></script>';
		}
			
		endforeach;
		}
		
			
	echo $after_widget;
}
endif;

?>