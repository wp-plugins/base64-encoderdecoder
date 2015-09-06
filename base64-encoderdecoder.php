<?php
/*
Plugin Name: Base64 Encoder/Decoder
Plugin URI: http://www.mrandersonmd.cl/wordpress-plugins/base64-encoderdecoder/
Description: Plugin for Base64 Encoding/Decoding into Wordpress. Use [base64 block_id=<number>] and [/base64], or [base64] and [/base64] if there's only one block, to enclose the content you want to encode inside your post.
Version: 0.9.2
Author: Edison Montes M.
Author URI: http://www.mrandersonmd.cl
License: GPL3
License URI: https://www.gnu.org/licenses/gpl.html
Domain Path: /languages
Text Domain: base64-encoderdecoder

VERSION HISTORY:

0.9.2 (Sep 05, 2015)
	* Cosmetic changes to Administration page
	* Added CSS styling to Administration page
	* Added Paypal button
	* Quicktag not working, removed temporarily
	* Bugs fixed
0.9.1 (Aug 10, 2015)
	* Plugin Header optimized according to WordPress Plugin Handbook
	* Internationalization according to WordPress Plugin Handbook
	* Deleted remote update function, it was redundant and innecesary now that the plugin is hosted by WordPress SVN
0.9 (Jul 31, 2015)
	* Shortcode recode according to actual Codex instructions
	* Quicktag recode according to actual Codex instructions
	* Deleted legacy code no longer needed
0.8.5 (Aug 01, 2009)
	* Added Internationalization file
0.8.2 (Mar 13, 2009)
	* Minor bug related to remote version check fixed
	* Optimization of minor parts of the code
0.8 (Mar 01, 2009)
	* Added AJAX inline text replacement
0.7.1 (Feb 25, 2009)
	* Fixed a bug related to multiple base64 blocks showing on different posts at the same time
0.7 (Feb 03, 2009)
	* Database update function from old tags to new ones
0.6.1 (Feb 02, 2009)
	* Fixed some bugs related to double quotes inside a base64 block
	* Deleted redundant and unnecesary code
0.6 (Jan 30, 2009)
	* Inline replacement, no need for different flavors
	* Removed post title variable because of inline replacement
	* New tag format html-styled with retro-compatibility
	* Revamped configuration page
0.4.2 (Jul 16, 2007)
	* Minor quicktag bug fixed
0.4 (Nov 13, 2006)
	* Added checking for new versions
	* Added Quicktags button and configurable activation/deactivation
0.3 (Oct 26, 2006)
	* Added options screen
	* Configurable wordwrap, text block html formatting, new post title and submit button text
0.2 (Oct 23, 2006)
	* Multiple base64 encoded blocks
	* Optimized checking for paired tag formatting
0.1 (Oct 21, 2006)
	* First release
	
CREDITS:

Most parts of the code are not my creation, they were borrowed from people smarter than me, so I must thank to them.

Thanks to aNieto2k's AntiTroll Plugin for part of the code, because that was my first source when I knew nothing about creating a Wordpress Plugin. (http://www.anieto2k.com/2006/02/08/plugin-antitroll/)

Thanks to Random Snippets for the Javascript replacement script. (http://www.randomsnippets.com/2008/03/07/how-to-find-and-replace-text-dynamically-via-javascript/)

Thanks to Lorelle's Blog for the info on how to search and replace inside a Wordpress database. (http://lorelle.wordpress.com/2005/12/01/search-and-replace-in-wordpress-mysql-database/)

Thanks to MyDigitalLife for the info on how to identify the postID, helping me to solve a bug related to multiple base64 blocks showing on different posts at same time. (http://www.mydigitallife.info/2006/06/24/retrieve-and-get-wordpress-post-id-outside-the-loop-as-php-variable/)

Thanks to Daniel Lorch for the info on how to use AJAX inside the plugin, it was a clarificating example. (http://daniel.lorch.cc/docs/ajax_simple/)

Thanks to Automatic Timezone Plugin for parts of script that adds "Settings" link to Admin Page in Installed Plugins Page. (http://wordpress.org/extend/plugins/automatic-timezone/)

Thanks to Famfamfam for the key icon used for the Admin page. (http://www.famfamfam.com/lab/icons/silk/)

*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

load_plugin_textdomain('base64-encoderdecoder', false, basename( dirname( __FILE__ ) ) . '/languages');
add_action('wp_head','b64_add_header');
add_action('admin_menu','b64_admin_page');
add_action( 'admin_enqueue_scripts', 'b64_css_styles' );
add_shortcode('base64', 'b64_shortcode');

// DEFINE CSS STYLING
function b64_css_styles() {
    wp_register_style( 'b64-css-style', plugins_url( '/css/base64-encoderdecoder.css', __FILE__ ), array(), '1.0', 'all' );
    wp_enqueue_style( 'b64-css-style' );
}

// SHORTCODE CREATION
function b64_shortcode( $b64_atts, $content = null ) {

	if ($b64_atts == null) {
		$b64_atts['block_id'] = get_option(b64_block_id);
	} else {
		extract(shortcode_atts(array('block_id' => 'block_id'), $b64_atts));
	}
	
  global $wp_query;
  $b64_wordwrap = get_option(b64_wordwrap);
  $b64_button_text = get_option(b64_button_text);

// ENCODING, WORDWRAPING & HTML FORMATTING
  
  $thePostID = $wp_query->post->ID;
  $encoded_data = base64_encode(htmlentities($content, ENT_QUOTES));
  $b64_block = wordwrap($encoded_data, $b64_wordwrap, "<br />\n", 1);
  
$html = "<div id='b64block-" . $thePostID . "-" . $b64_atts['block_id'] . "'>";
  if ($format=='bq') {
    $html .= "<blockquote><p>" . $b64_block . "</p></blockquote>";
  } elseif ($format=='cd') {
    $html .= "<code><p>" . $b64_block . "</p></code>";
  } else {
    $html .= "<p>" . $b64_block . "</p>";
  }
  $html .= '<input type="button" value="' . $b64_button_text . '" name="send" onClick="javascript:replaceb64Text(\'b64block-' . $thePostID . '-' . $b64_atts['block_id'] . '\', \'' . $encoded_data . '\');"></div>';
  
return $html;
        
}

// ADD EXTERNAL DECODE SUBROUTINE JAVASCRIPT TO THE HEADER
function b64_add_header() {
  echo "\n<!-- Start of script generated by Base64 Encoder/Decoder Plugin -->\n";
  echo "<script language=\"JavaScript\" type=\"text/javascript\">

var http = false;

if(navigator.appName == \"Microsoft Internet Explorer\") {
  http = new ActiveXObject(\"Microsoft.XMLHTTP\");
} else {
  http = new XMLHttpRequest();
}

function replaceb64Text(b64block, encstring) {
  http.abort();
  http.open(\"GET\", \"" . WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__) ) . "/base64-decode.php?string=\" + encstring, true);
  http.onreadystatechange=function() {
    if(http.readyState == 4) {
      document.getElementById(b64block).innerHTML = http.responseText;
    }
  }
  http.send(null);
}
</script>";
echo "\n<!-- End of script generated by Base64 Encoder/Decoder Plugin -->\n";
}
  
// Adds Admin page
function b64_admin_page() {
	global $wp_version;
if ( current_user_can('manage_options') && function_exists('add_options_page') ) {
	
		$menutitle = '';
		if ( version_compare( $wp_version, '2.6.999', '>' ) ) {
	  		$menutitle = '<img src="'.plugins_url( '/images/key-gray.png', __FILE__ ).'" style="margin-right:4px;" />';
		}
		$menutitle .= __('Base64 Enc/Dec', 'base64-encoderdecoder');
		add_options_page(__('Base64 Enc/Dec Configuration', 'base64-encoderdecoder'), $menutitle , 'manage_options', __FILE__, 'b64_config');
		add_action( 'admin_init', 'b64_register_settings' );
		add_filter( 'plugin_action_links', 'b64_filter_plugin_actions', 10, 2 );
	}
}

// Options page
function b64_config() {

  include ('base64-options.php');
  
}

function b64_register_settings() {
// ACCEPTED OPTIONS

	$b64_defaults = array ('b64_wordwrap' => 55, 'b64_format' => 'bq', 'b64_button_text' => __('Decode', 'base64-encoderdecoder'), 'b64_button_option' => off, 'b64_block_id' => 1);
	global $b64_defaults;
	
	register_setting( 'b64-encoderdecoder', 'b64_wordwrap' );
	register_setting( 'b64-encoderdecoder', 'b64_format' );
	register_setting( 'b64-encoderdecoder', 'b64_button_text' );
	register_setting( 'b64-encoderdecoder', 'b64_button_option' );
	register_setting( 'b64-encoderdecoder', 'b64_block_id' );
	
	add_option( 'b64_wordwrap', $b64_defaults['b64_wordwrap'] );
	add_option( 'b64_format', $b64_defaults['b64_format'] );
	add_option( 'b64_button_text', $b64_defaults['b64_button_text'] );
	add_option( 'b64_button_option', $b64_defaults['b64_button_option'] );
	add_option( 'b64_block_id', $b64_defaults['b64_block_id'] );
}

// OLD TAG FORMAT DATABASE CHECK
function b64_old_tag_check() {
	global $wpdb;
	
	// FIRST CHECK
	$search = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_content LIKE '%<!--base64-->%' ORDER BY post_content", OBJECT);
	$results = count($search);
	if ($results > 0) {
		return 1;
	} else {
		return 0;
	}
	
	// SECOND CHECK
	$search = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_content LIKE '%<base64>%' ORDER BY post_content", OBJECT);
	$results = count($search);
	if ($results > 0) {
		return 1;
	} else {
		return 0;
	}
}

// UPDATE DATABASE FUNCTION
function b64_update_db() {
	global $wpdb;
	$wpdb->query("UPDATE wp_posts SET post_content = REPLACE (post_content,'<!--base64-->','[base64]')");
	$wpdb->query("UPDATE wp_posts SET post_content = REPLACE (post_content,'<!--/base64-->','[/base64]')");
	$wpdb->query("UPDATE wp_posts SET post_content = REPLACE (post_content,'<base64>','[base64]')");
	$wpdb->query("UPDATE wp_posts SET post_content = REPLACE (post_content,'</base64>','[/base64]')");
}

function b64_filter_plugin_actions($links, $file){
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if( $file == $this_plugin ) {
		$settings_link = '<a href="admin.php?page=b64-config">' . __('Settings', 'base64-encoderdecoder') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}

function b64_plugin_version() {
    $plugin_data = get_plugin_data( __FILE__ );
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}
?>