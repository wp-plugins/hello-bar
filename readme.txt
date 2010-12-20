=== Hello Bar ===
Contributors: austyfrosty
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDD3EDC28RAWS
Tags: admin, bar, hello, stats
Requires at least: 3.0
Tested up to: 3.2
Stable tag: trunk

A fixed position (header) jQuery pop-up announcemnet bar with Custom Post Types.

== Description ==

This plugin adds a javascript file that will position a fixed bar at the top of your browser screen to show announcements (controlled by a custom post type [CPT]) on each page load. Built with simple HTML and javascript. Please note that this plugin is in no way affiliated with the [Hello Bar](http://www.hellobar.com).

Upon installation, you can choose the prefix for your post type. Be sure to choose wisely, because once you publish your first post this can't be changed. If you are having problems with redirects visit your *permalinks* page to flush the rewrite rules (no save needed).

For question please visit my blog @ [http://austinpassy.com](http://austinpassy.com/wordpress-plugins/hello-bar/)

This plugin is still under **&alpha;**

== Installation ==

Follow the steps below to install the plugin.

1. Upload the `hello-bar` directory to the /wp-content/plugins/ directory. OR click add new plugin in your WordPress admin.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Hello Bar settings to edit your settings.

== Changelog ==

= Version 0.05 (12/19/10) =

* Fixed issue with `slug` stuck in readonly setting.

= Version 0.04 (12/19/10) =

* Removed scripts and styles from the admin.
* Removed filter, used during testing.

= Version 0.03 (12/19/10) =

* Readme update.
* Added Custom Post Type.
* Added options panel.
* Added jQuery & CSS.

**TODO**

* Check rewrite rules flushing proper on *slug* change.
* Better stats.

= Version 0.02&alpha; (12/15/10) =

* Readme update.

== Upgrade Notice ==

= 0.05 =
Added missing files. Now has Custom Post Type and options screen & bug fix.