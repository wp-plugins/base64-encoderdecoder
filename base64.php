<?php
/*
Plugin Name: Base64 Encoder/Decoder
Plugin URI: http://www.mrandersonmd.cl/wordpress-plugins/base64-encoderdecoder-plugin-for-wordpress/
Description: Plugin for Base64 Encoding/Decoding into Wordpress. Use [base64 block_id=<number>] and [/base64], or [base64] and [/base64] if there's only one block, to enclose the content you want to encode inside your post.
Version: 0.9
Author: Edison Montes M.
Author URI: http://www.mrandersonmd.cl
License: GPL

VERSION HISTORY:

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
	* First release, just functional
	
CREDITS:

Most parts of the code are not my creation, they were borrowed from people smarter than me, so I must thank to them.

Thanks to aNieto2k's AntiTroll Plugin for part of the code, because that was my first source when I knew nothing about creating a Wordpress Plugin. (http://www.anieto2k.com/2006/02/08/plugin-antitroll/)

Thanks to Random Snippets for the Javascript replacement script. (http://www.randomsnippets.com/2008/03/07/how-to-find-and-replace-text-dynamically-via-javascript/)

Thanks to Lorelle's Blog for the info on how to search and replace inside a Wordpress database. (http://lorelle.wordpress.com/2005/12/01/search-and-replace-in-wordpress-mysql-database/)

Thanks to MyDigitalLife for the info on how to identify the postID, helping me to solve a bug related to multiple base64 blocks showing on different posts at same time. (http://www.mydigitallife.info/2006/06/24/retrieve-and-get-wordpress-post-id-outside-the-loop-as-php-variable/)

Thanks to Daniel Lorch for the info on how to use AJAX inside the plugin, it was a clarificating example. (http://daniel.lorch.cc/docs/ajax_simple/)

Thanks to Automatic Timezone Plugin for parts of script that adds "Settings" link to Admin Page in Installed Plugins Page. (http://wordpress.org/extend/plugins/automatic-timezone/)

Thanks to Famfamfam for the key icon used for the Admin page. (http://www.famfamfam.com/lab/icons/silk/)


LICENSE:

Copyright 2006-2015  Edison Montes M.  (email : webmaster@mrandersonmd.cl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

load_plugin_textdomain('base64-encoderdecoder', false, basename( dirname( __FILE__ ) ) . '/');
include_once(ABSPATH . WPINC . '/class-snoopy.php');

add_shortcode('base64', 'b64_shortcode');

add_action('wp_head','b64_add_header');
add_action('admin_menu','b64_admin_page');

// SHORTCODE CREATION
function b64_shortcode( $atts , $content = null ) {

// ACCEPTED OPTIONS & DEFAULT VALUES
$default_wordwrap = 55;
$default_format = 'bq';
$default_button_text = 'Decode';
$default_button_option = on;
$default_block_id = 1;

    extract( shortcode_atts( array(
        'wordwrap' => $default_wordwrap,
        'format' => $default_format,
        'button_text' => $default_button_text,
        'button_option' => $default_button_option,
        'block_id' => $default_block_id
    ), $atts ) );

// ENCODING, WORDWRAPING & HTML FORMATTING
global $wp_query;
  $thePostID = $wp_query->post->ID;
  $encoded_data = base64_encode(htmlentities($content, ENT_QUOTES));
  $b64_block = wordwrap($encoded_data, $wordwrap, "<br />\n", 1);
  
$html = "<div id='b64block-" . $thePostID . "-" . $block_id . "'>";
  if ($format=='bq') {
    $html .= "<blockquote><p>" . $b64_block . "</p></blockquote>";
  } elseif ($format=='cd') {
    $html .= "<code><p>" . $b64_block . "</p></code>";
  } else {
    $html .= "<p>" . $b64_block . "</p>";
  }
  $html .= '<input type="button" value="' . $button_text . '" name="send" onClick="javascript:replaceb64Text(\'b64block-' . $thePostID . '-' . $block_id . '\', \'' . $encoded_data . '\');"></div>';
  
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
  http.open(\"GET\", \"" . WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__) ) . "/base64_decode.php?string=\" + encstring, true);
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

// QUICKTAG CREATION

// Adds Javascript functions
function wp_b64_add_js() {
  $b64_button_option = get_option(wp_b64_button_option);
  if ((strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php') || strpos($_SERVER['REQUEST_URI'], 'page-new.php')) && $b64_button_option == 'on') {
  	?>
  	<script language="JavaScript" type="text/javascript">
	<!--
          var toolbar = document.getElementById("ed_toolbar");

          if(toolbar) {
            var theButton = document.createElement('input');
            theButton.type = 'button';
            theButton.value = 'base64';
            theButton.onclick = wp_b64_add_tag_button;
            theButton.className = 'ed_button';
            theButton.title = 'Insert base64 encoded text block';
            theButton.id = 'ed_base64';
            toolbar.appendChild(theButton);
            }

	  function wp_b64_add_tag_button() {
	    edInsertContent(edCanvas, '[base64]');
            var theButton = document.getElementById("ed_base64");
            theButton.value = '/base64';
            theButton.onclick = wp_b64_rem_tag_button;
	  }

      function wp_b64_rem_tag_button() {
        edInsertContent(edCanvas, '[/base64]');
            var theButton = document.getElementById("ed_base64");
            theButton.value = 'base64';
            theButton.onclick = wp_b64_add_tag_button;
          }
	//--></script>
	<?php
  }
}
  
// Adds Admin page
function b64_admin_page() {
	global $wp_version;
if ( current_user_can('manage_options') && function_exists('add_options_page') ) {
	
		$menutitle = '';
		if ( version_compare( $wp_version, '2.6.999', '>' ) ) {
	  		$menutitle = '<img src="'.plugins_url(dirname(plugin_basename(__FILE__))).'/key.png" style="margin-right:4px;" />';
		}
		$menutitle .= __('Base64 Enc/Dec', 'base64-encoderdecoder');
		add_options_page(__('Base64 Enc/Dec Configuration', 'base64-encoderdecoder'), $menutitle , 'manage_options', 'b64-config', 'b64_config');
		add_filter( 'plugin_action_links', 'b64_filter_plugin_actions', 10, 2 );
	}
}

// Options page
function b64_config() {
  if (isset($_POST['update'])) {
    update_option('wordwrap', $_POST['wordwrap']);
    update_option('format', $_POST['format']);
    update_option('button_text', $_POST['button_text']);
    update_option('button_option', $_POST['button_option']);
    echo "<div style=\"background-color: rgb(207, 235, 247);\" id=\"message\" class=\"updated fade\"><p><strong>".__('Options Updated', 'base64-encoderdecoder')."</strong></p></div>";
  }
  if (isset($_POST['reset'])) {
    update_option('wordwrap', default_wordwrap);
    update_option('format', default_format);
    update_option('button_text', default_button_text);
    update_option('button_option', default_button_option);
    echo "<div style=\"background-color: rgb(207, 235, 247);\" id=\"message\" class=\"updated fade\"><p><strong>".__('Options Reseted', 'base64-encoderdecoder')."</strong></p></div>";
  }
  if (isset($_POST['updatedb'])) {
	 b64_update_db();
    echo "<div style=\"background-color: rgb(207, 235, 247);\" id=\"message\" class=\"updated fade\"><p><strong>".__('Database updated to new tag format', 'base64-encoderdecoder')."</strong></p></div>";
  }
  if (($remote = b64_remote_version_check()) == 1) {
	$b64_homeurl = b64_info('homeurl');
	$b64_homename = b64_info('homename');
	$b64_downloadurl = b64_info('downloadurl') . b64_info('remoteversion');
  	printf("<div style=\"background-color: rgb(207, 235, 247);\" id=\"message\" class=\"updated fade\"><p>".__('There is a <strong><a href=\"%1$s\" title=\"%2$s\">NEW</a></strong> version available. You can download it <a href=\"%3$s.zip\">HERE</a>', 'base64-encoderdecoder')."</p></div>", $b64_homeurl, $b64_homename, $b64_downloadurl);
  }
  $b64_format = get_option(b64_format);
  $b64_button_option = get_option(b64_button_option);
  ?>
  <div class="wrap">
<?php
echo "<h2>".__('Base64 Encoder/Decoder Options', 'base64-encoderdecoder')."</h2>";
?>
    <br />
    <form name="b64_options" method="post">
<?php
echo "<h3>".__('Display Options', 'base64-encoderdecoder')."</h3>";
?>
	 <table class="form-table">
	 <tr valign="top">
<?php
echo "<th scope=\"row\">".__('Button Text', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Button Text', 'base64-encoderdecoder')."</legend><label for=\"button_text\"><input name=\"" . wp_b64_button . "\" value=\"" . get_option(wp_b64_button) . "\" size=\"40\" class=\"code\" type=\"text\" /> ";
echo __('Text of the submit button', 'base64-encoderdecoder')."</label><br /></fieldset></td>";
?>
    </tr><tr valign="top">
<?php
echo "<th scope=\"row\">".__('Wordwrap', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Wordwrap', 'base64-encoderdecoder')."</legend><label for=\"wordwrap\"><input name=\"" . wp_b64_wordwrap . "\" value=\"" . get_option(wp_b64_wordwrap) . "\" size=\"40\" class=\"code\" type=\"text\" /> ";
echo __('How many characters per line you want', 'base64-encoderdecoder')."</label><br /></fieldset></td>";
?>
    </tr><tr valign="top">
<?php
echo "<th scope=\"row\">".__('Block Format', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Block Format', 'base64-encoderdecoder')."</legend><label for=\"block_format\"><select name=\"" . wp_b64_format . "\"><option value=\"bq\"";
if($b64_format=='bq'){echo ' selected';}
echo ">".__('Blockquote', 'base64-encoderdecoder')."</option>";
echo "<option value=\"cd\"";
if($b64_format=='cd'){echo ' selected';}
echo ">".__('Code', 'base64-encoderdecoder')."</option>";
echo "<option value=\"no\"";
if($b64_format=='no'){echo ' selected';}
echo ">".__('None', 'base64-encoderdecoder')."</option></select> ";
echo __('Choose the html format for the text block', 'base64-encoderdecoder')."</label><br /></fieldset></td>";
?>
    </tr>
    </table>
<?php
echo "<h3>".__('Editing Options', 'base64-encoderdecoder')."</h3>";
?>
	 <table class="form-table">
	 <tr valign="top">
<?php
echo "<th scope=\"row\">".__('Display Post Button', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Display Post Button', 'base64-encoderdecoder')."</legend><label for=\"display_post_button\">";
echo "<input name=\"" . b64_button_option . "\" type=\"checkbox\" value=\"on\"";
if ($b64_button_option=='on'){ echo ' checked';}
echo " /> ".__('Hide/unhide the post button when you edit the post', 'base64-encoderdecoder')."</label><br /></fieldset></td>";
?>
	</tr>
    </table>
<?php
  if (($oldtagcheck = b64_old_tag_check()) == 1) {
  	echo "<h3>".__('Old Tag Format', 'base64-encoderdecoder')."</h3>";
	echo "<p>".__('The configuration has detected and old tag format inside your WordPress database. If you wish to update to the new tag format, BACKUP YOUR DATABASE PREVIOUSLY and then press the <em><strong>Update Database</strong></em> button.', 'base64-encoderdecoder')."</p>";
  }
?>
    <p class="submit">
<?php
echo "<input type=\"submit\" name=\"update\" value=\"".__('Update Options', 'base64-encoderdecoder')."\" />&nbsp; ";
echo "<input type=\"submit\" name=\"reset\" value=\"".__('Reset Options', 'base64-encoderdecoder')."\" />";
  if (($oldtagcheck = b64_old_tag_check()) == 1) {
  	echo "&nbsp; <input type=\"submit\" name=\"updatedb\" value=\"".__('Update Database', 'base64-encoderdecoder')."\" />";
  	}
?>  </p>
    </form>
  </div>
  <?php
}

function b64_remote_version_check()
{  
  	$remote = b64_info('remoteversion');
  	if (!$remote) {
  		return -1;
  	} else {
	  	return version_compare($remote, b64_info('localeversion'));
	}
}

// INFORMATION FUNCTION
function b64_info($show = '') {
  switch($show) {
    case 'localeversion':
      $info = '0.8.5';
      break;
    case 'homeurl':
      $info = 'http://www.mrandersonmd.cl/wordpress-plugins/base64-encoderdecoder-plugin-for-wordpress/';
      break;
	 case 'downloadurl':
	 	$info = 'http://www.mrandersonmd.cl/files/plugins/base64-encoderdecoder.';
	 	break;
    case 'homename':
      $info = 'MrAnderson MD';
      break;
    case 'remoteversionfile':
      $info = 'http://www.mrandersonmd.cl/files/plugins/wp-base64-version.txt';
      break;
	 case 'remoteversion':
	   $info = b64_remote_version();
	   break;
    default:
      $info = '';
      break;
    }
  return $info;
}

// CHECK FOR NEW VERSIONS
function b64_remote_version() {
  if (class_exists(snoopy)) {
  	$client = new Snoopy();
  	$client->_fp_timeout = 4;
  	if ($client->fetch(b64_info('remoteversionfile')) === false ) {
		return -1;
	}
	$remote = $client->results;
	return $remote;
	}
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
?>
