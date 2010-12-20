<?php
	
	error_reporting(0);
	if ( $returnCSS ) {
		ob_start();
	} else {
		header("Content-type: text/css; charset: UTF-8");
		/** 
		 * Find wp-load.php
		 * defualt location from this directory is 
		 * "../../../../../wp-load.php"
		 */
		if ( file_exists( '../../../../../wp-load.php' ) ) {
			
			require_once( '../../../../../wp-load.php' );
			
		}
		if ( file_exists( '../../../../wp-load.php' ) ) {
			
			require_once( '../../../../wp-load.php' );
			
		}
	}
	global $wpdb, $hello_bar; ?>
/**
 * Hello Bar
 * @use: hello_bar_get_setting( '' );
 */

/* Start custom user input */
<?php echo wp_specialchars_decode( stripslashes( hello_bar_get_setting( 'custom_css' ) ), 1, 0, 1 ) . "\n\n"; ?>
/* End custom user input */

/*
body {
	padding-top: <?php if ( is_admin_bar_showing() ) echo hello_bar_get_setting( 'height' ) + 28 . 'px'; else echo hello_bar_get_setting( 'height' );  ?> !important;
}
*/

/* Extra div to handle some IE scenarios with absolute positioning. */
#hello-bar-container {
	display: table;
	position: relative;
	width: 100%;
	z-index: 99996;
}

#hello-bar {
	background: <?php echo hello_bar_get_setting( 'background' ); ?>;
    color: <?php echo hello_bar_get_setting( 'color' ); ?>;
    direction: ltr;
    font: 12px Arial,Helvetica,sans-serif;
    height: <?php echo hello_bar_get_setting( 'height' ); ?>;
    left: 0;
    margin: 0 auto;
    min-width: 960px;
    position: fixed;
    top: -<?php echo hello_bar_get_setting( 'height' ); ?>;
    width: 100%;
    -moz-box-shadow: 0 0 15px rgba(0,0,0,0.3);
    -webkit-box-shadow: 0 0 15px rgba(0,0,0,0.3);
	box-shadow: 0 0 15px rgba(0,0,0,0.3);
}
#hello-bar.no-js {
}

.hello {
    color: <?php echo hello_bar_get_setting( 'color' ); ?>;
    display: block;
    font-size: <?php echo hello_bar_get_setting( 'size' ); ?>;
	line-height: <?php echo hello_bar_get_setting( 'height' ); ?>;
    margin: 0 auto;
    text-align: center;
    width: 860px;
	z-index: 99999;
}
.hello p {
	z-index: 99999;
}
.hello a {
    color: <?php echo hello_bar_get_setting( 'a_color' ); ?>;
    text-decoration: none;
	z-index: 99999;
}

/* Branding link. */
#hello-bar-container .branding {
    margin: 0 auto;
    width: 960px;
	z-index: 99999;
}
#hello-bar-container .branding a {
	background: 0 none;
	display: block;
    float: left;
	width: 30px;
	height: <?php echo hello_bar_get_setting( 'height' ); ?>;
	margin: 0;
	padding: 0;
    <?php if ( hello_bar_get_setting( 'height' ) == '28px' ) echo "font: normal normal normal 26px/26px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( hello_bar_get_setting( 'height' ) == '33px' ) echo "font: normal normal normal 30px/31px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( hello_bar_get_setting( 'height' ) == '40px' ) echo "font: normal normal normal 34px/34px Georgia, Times, 'Times New Roman', serif !important;"; ?>
	color: #fff;
 	text-align: center;
	position: absolute;
    text-decoration: none;
    text-shadow: 0 1px 0 #000;
    -moz-text-shadow: 0 1px 0 #000;
    -webkit-text-shadow: 0 1px 0 #000;
    top: 0;
	z-index: 99999;
}

/* Toggle div wrapper. */
#hello-bar-container .tab {
	height: 0;
	position: fixed;
	top: 0;
    width: 100%;
	z-index: 99997;
}

/* Wrapper for the open/close button. */
#hello-bar-container .tab .toggle {
  	clear: both;
	display: block;
	position: relative;
	width: 960px;
	/*height: <?php echo hello_bar_get_setting( 'height' ); ?>;*/
	/*line-height: <?php echo hello_bar_get_setting( 'height' ); ?>;*/
	margin: 0 auto;
	z-index: 99997;
}

/* Open/close link. */
#hello-bar-container .tab a {
  	background: rgba(0,0,0,0.1);
	display: block;
	float: right;
	position: relative;
	width: 30px;
	height: <?php echo hello_bar_get_setting( 'height' ); ?>;
	margin: 0;
	padding: 0;
    <?php if ( hello_bar_get_setting( 'height' ) == '28px' ) echo "font: normal normal bold 20px/22px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( hello_bar_get_setting( 'height' ) == '33px' ) echo "font: normal normal bold 24px/24px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( hello_bar_get_setting( 'height' ) == '40px' ) echo "font: normal normal bold 31px/27px Georgia, Times, 'Times New Roman', serif !important;"; ?>
	color: #fff;
 	text-align: center;
}

/* Open link. */
#hello-bar-container .tab a.open {
	-moz-border-radius: 0 0 5px 5px;
	-webkit-border-radius: 0 0 5px 5px;
	border-radius: 0 0 5px 5px;
}
#hello-bar-container .tab a.open:hover {
  	background: rgba(0,0,0,0.4);
}

/* Close link. */
#hello-bar-container .tab a.close {
}	

/* Open/close link hover. */
#hello-bar-container .bradning a:hover, #hello-bar-container .tab a:hover {
	cursor: pointer;
	text-decoration: none;
}

/* Open/close link arrows. */
#hello-bar-container .tab a .arrow {
	font-style: normal;
}

<?php if ( $returnCSS ) $css = ob_get_clean(); ?>