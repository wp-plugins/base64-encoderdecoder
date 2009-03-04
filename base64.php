<?php
/*
Plugin Name: WP-Base64 Encoder/Decoder
Plugin URI: http://www.mrandersonmd.com/wordpress-plugins/base64-encoderdecoder-plugin-for-wordpress/
Description: Plugin for Base64 Encoding/Decoding into Wordpress
Version: 0.7.1
Author: Edison Montes M.
Author URI: http://www.mrandersonmd.com
License: GPL

Version History:

0.7.1 (25 Feb 2009)
	* Fixed a bug related to multiple base64 blocks showing on different posts at the same time
0.7 (03 Feb 2009)
	* Database update function from old tags to new ones
0.6.1 (02 Feb 2009)
	* Fixed some bugs related to double quotes inside a base64 block
	* Deleted redundant and unnecesary code
0.6 (30 Jan 2009)
	* Inline replacement, no need for different flavors
	* Removed post title variable because of inline replacement
	* New tag format html-styled with retro-compatibility
	* Revamped configuration page
0.4.2 (16 Jul 2007)
	* Minor quicktag bug fixed
0.4 (13 Nov 2006)
	* Added checking for new versions
	* Added Quicktags button and configurable activation/deactivation
0.3 (26 Oct 2006)
	* Added options screen
	* Configurable wordwrap, text block html formatting, new post title and submit button text
0.2 (23 Oct 2006)
	* Multiple base64 encoded blocks
	* Optimized checking for paired tag formatting
0.1 (21 Oct 2006)
	* First release, just functional
	
Credits:

Most parts of the code are not my creation, they were borrowed from people smarter than me, so I must thank to them.

Thanks to aNieto2k's AntiTroll Plugin for part of the code, because that was my first source when I knew nothing about creating a Wordpress Plugin. (http://www.anieto2k.com/2006/02/08/plugin-antitroll/)

Thanks to Random Snippets for the Javascript replacement script. (http://www.randomsnippets.com/2008/03/07/how-to-find-and-replace-text-dynamically-via-javascript/)

Thanks to Lorelle's Blog for the info on how to search and replace inside a Wordpress database. (http://lorelle.wordpress.com/2005/12/01/search-and-replace-in-wordpress-mysql-database/)

Thanks to MyDigitalLife for the info on how to identify the postID, helping me to solve the bug related to multiple base64 blocks showing on different posts at same time. (http://www.mydigitallife.info/2006/06/24/retrieve-and-get-wordpress-post-id-outside-the-loop-as-php-variable/)

License:

    Copyright 2006-2009  Edison Montes M.  (email : webmaster@mrandersonmd.com)

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

include_once(ABSPATH . WPINC . '/class-snoopy.php');

wp_b64_variables();
wp_b64_init();

add_filter('the_content','wp_b64_tag');
add_action('admin_menu','wp_b64_admin_page');
add_filter('admin_footer', 'wp_b64_add_js');
add_action('wp_head','wp_b64_add_header');

// Main function
function wp_b64_tag($content) {
  if ( strchr($content, '<base64>') == null && strchr($content, '<!--base64-->') == null) {
    return $content;
  } else {
    // checks for paired tag
    $retbase64 = '';
    $counta1 = substr_count($content, '<base64>');
    $counta2 = substr_count($content, '</base64>');
    $countb1 = substr_count($content, '<!--base64-->');
    $countb2 = substr_count($content, '<!--/base64-->');
    $totalcount1 = $counta1 + $countb1;
    $totalcount2 = $counta2 + $countb2;
    if ($totalcount1 == $totalcount2) {
	  $arrContent[0] = $content;
	  $counter = $totalcount1;
	  $countera = $counta1;
	  $counterb = $countb1;
	  $whilecount = "1";
     while ($whilecount <= $counter) {
     	while ($countera >= 1) {
        $arrRetVal = explode('<base64>', $arrContent [0], 2);
        $retbase64 .= $arrRetVal[0];
        $arrRetVal = explode('</base64>', $arrRetVal[1], 2);
		  $arrRetVal[0] = str_replace('"',"'",$arrRetVal[0]);
        $retbase64 .= wp_b64_encode($arrRetVal[0], $whilecount);
        $arrContent[0] = $arrRetVal[1];
        $whilecount++;
        $countera--;
			}
      while ($counterb >= 1) {      
        $arrRetVal = explode('<!--base64-->', $arrContent[0], 2);
        $retbase64 .= $arrRetVal[0];
        $arrRetVal = explode('<!--/base64-->', $arrRetVal[1], 2);
		  $arrRetVal[0] = str_replace('"',"'",$arrRetVal[0]);
        $retbase64 .= wp_b64_encode($arrRetVal[0], $counter);
        $arrContent[0] = $arrRetVal[1];
        $whilecount++;
        $counterb--;
			}
      }
		$retbase64 .= $arrRetVal[1];
      return $retbase64;
    } else {
      return $content;
    }
  }
}

// Information function
function wp_b64_info($show = '') {
  switch($show) {
    case 'localeversion':
      $info = '0.64';
      break;
    case 'homeurl':
      $info = 'http://www.mrandersonmd.com/wordpress-plugins/base64-encoderdecoder-plugin-for-wordpress/';
      break;
	 case 'downloadurl':
	 	$info = 'http://www.mrandersonmd.com/files/plugins/wp-base64v';
	 	break;
    case 'homename':
      $info = 'MrAnderson MD';
      break;
    case 'remoteversionfile':
      $info = 'http://www.mrandersonmd.com/files/plugins/wp-base64-version.txt';
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

// Encodes part of the post
function wp_b64_encode($string_to_encode, $i) {
  $encoded_data = base64_encode(utf8_encode($string_to_encode));
  $wp_b64_html = wp_b64_html($encoded_data, $i);
  return $wp_b64_html;
}

// Gives html format to the encoded text block
function wp_b64_html($encoded_data, $i) {
  global $wp_query;
  $thePostID = $wp_query->post->ID;
  $wp_b64_wordwrap = get_option('wp_b64_wordwrap');
  $wp_b64_format = get_option('wp_b64_format');
  $wp_b64_button = get_option('wp_b64_button');
  $bq_formatted = wordwrap($encoded_data, $wp_b64_wordwrap, "<br />\n", 1);
  $decoded_data = wp_b64_format_post(utf8_decode(base64_decode($encoded_data)));
  $retval = "<div id='b64block-" . $thePostID . "-" . $i . "'>";
  if ($wp_b64_format=='bq') {
    $retval .= "<blockquote><p>" . $bq_formatted . "</p></blockquote>";
  } elseif ($wp_b64_format=='cd') {
    $retval .= "<code><p>" . $bq_formatted . "</p></code>";
  } else {
    $retval .= "<p>" . $bq_formatted . "</p>";
  }
  $retval .= '<br /><input type="hidden" id="decstring-' . $thePostID . '-' . $i . '" name="decstring-' . $thePostID . '-' . $i . '" value="' . $decoded_data . '" />';
  $retval .= '<input type="button" value="' . $wp_b64_button . '" name="send" onClick="javascript:replaceb64Text(\'b64block-' . $thePostID . '-' . $i . '\', \'decstring-' . $thePostID . '-' . $i . '\');"></div>';

  return $retval;
}

// Gives html format to decoded page
function wp_b64_format_post($decoded_data) {
  $retval = "<p>";
  $retval .= str_replace("\n", "</p><p>", $decoded_data);
  $retval .= "</p>";
  return $retval;
}

// Define variables
function wp_b64_variables(){
define('wp_b64_wordwrap_default', '55', true);
define('wp_b64_format_default', 'bq', true);
define('wp_b64_button_default', 'Decode', true);
define('wp_b64_button_option_default', 'on', true);

define('wp_b64_wordwrap', 'wp_b64_wordwrap', true);
define('wp_b64_format', 'wp_b64_format', true);
define('wp_b64_button', 'wp_b64_button', true);
define('wp_b64_button_option', 'wp_b64_button_option', true);

$wp_b64_wordwrap;
$wp_b64_format;
$wp_b64_button;
$wp_b64_button_option;
}

// Initiates all variables
function wp_b64_init() {
  $wp_b64_wordwrap = get_option(wp_b64_wordwrap);
  if (!$wp_b64_wordwrap) {
    add_option(wp_b64_wordwrap, wp_b64_wordwrap_default);
    add_option(wp_b64_format, wp_b64_format_default);
    add_option(wp_b64_button, wp_b64_button_default);
    add_option(wp_b64_title, wp_b64_title_default);
    add_option(wp_b64_button_option, wp_b64_button_option_default);
  }
  $wp_b64_wordwrap = get_option(wp_b64_wordwrap);
}

// Checks for new versions
function b64_remote_version() {
  if (class_exists(snoopy)) {
  	$client = new Snoopy();
  	$client->_fp_timeout = 4;
  	if ($client->fetch(wp_b64_info('remoteversionfile')) === false ) {
		return -1;
	}
	$remote = $client->results;
	return $remote;
	}
}

function b64_remote_version_check() {  
  	$remote = wp_b64_info('remoteversion');
  	if (!$remote || strlen($remote) > 8 ) {
  		return -1;
  	}
  	if (intval($remote) > intval(wp_b64_info('localeversion'))) {
  		return 1;
  	} else {
	  return 0;
	}
}

// Adds Javascript to the header
function wp_b64_add_header() {
  echo "\n<!-- Start of script generated by WP-Base64 Plugin -->\n";
  echo "<script language=\"JavaScript\" type=\"text/javascript\">
function replaceb64Text(b64block, decstring) {
     b64Text = document.getElementById(b64block);
     b64Text.innerHTML = document.getElementById(decstring).value;
     }
     
	</script>
		";
}

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
	    edInsertContent(edCanvas, '<base64>');
            var theButton = document.getElementById("ed_base64");
            theButton.value = '/base64';
            theButton.onclick = wp_b64_rem_tag_button;
	  }

      function wp_b64_rem_tag_button() {
        edInsertContent(edCanvas, '</base64>');
            var theButton = document.getElementById("ed_base64");
            theButton.value = 'base64';
            theButton.onclick = wp_b64_add_tag_button;
          }
	//--></script>
	<?php
  }
}

// Old tag format Database check
function wp_b64_old_tag_check() {
	global $wpdb;
	$search = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_content LIKE '%<!--base64-->%' ORDER BY post_content", OBJECT);
	$results = count($search);
	if ($results > 0) {
		return 1;
	} else {
		return 0;
	}
}

// Update Database function
function wp_b64_update_db() {
	global $wpdb;
	$wpdb->query("UPDATE wp_posts SET post_content = REPLACE (post_content,'<!--base64-->','<base64>')");
	$wpdb->query("UPDATE wp_posts SET post_content = REPLACE (post_content,'<!--/base64-->','</base64>')");
}

// Adds options page
function wp_b64_admin_page() {
  add_options_page('Base64 Enc/Dec', 'Base64 Enc/Dec', 9, __FILE__, 'wp_b64_config');
}

// Options page
function wp_b64_config() {
  if (isset($_POST['update'])) {
    update_option('wp_b64_wordwrap', $_POST['wp_b64_wordwrap']);
    update_option('wp_b64_format', $_POST['wp_b64_format']);
    update_option('wp_b64_button', $_POST['wp_b64_button']);
    update_option('wp_b64_button_option', $_POST['wp_b64_button_option']);
    echo "<div style='background-color: rgb(207, 235, 247);' id='message' class='updated fade'><p><strong>Options Updated</strong></p></div>";
  }
  if (isset($_POST['reset'])) {
    update_option('wp_b64_wordwrap', wp_b64_wordwrap_default);
    update_option('wp_b64_format', wp_b64_format_default);
    update_option('wp_b64_button', wp_b64_button_default);
    update_option('wp_b64_button_option', wp_b64_button_option_default);
    echo '<div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><strong>Options Reseted</strong></p></div>';
  }
  if (isset($_POST['updatedb'])) {
	 wp_b64_update_db();
    echo '<div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p><strong>Database updated to new tag format</strong></p></div>';
  }
  if (($remote = b64_remote_version_check()) == 1) {
  	echo '<div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p>There is a <strong><a href="' . wp_b64_info('homeurl') .'" title="' . wp_b64_info('homename') .'">NEW</a></strong> version available. You can download it <a href="' . wp_b64_info('downloadurl') . wp_b64_info('remoteversion') . '.zip">HERE</a></p></div>';
  }
  $b64_format = get_option(wp_b64_format);
  $b64_button_option = get_option(wp_b64_button_option);
  ?>
  <div class="wrap">
    <h2>Base64 Encoder/Decoder Options</h2>
    <br />
    <form name="wp_b64_options" method="post">
    <h3>Display Options</h3>
	 <table class="form-table">
	 <tr valign="top">
	 <th scope="row">Button Text</th>
    <td><fieldset><legend class="hidden">Button Text</legend><label for="button_text"><input name="<?php echo wp_b64_button; ?>" value="<?php echo get_option(wp_b64_button); ?>" size="40" class="code" type="text" /> Text of the submit button</label><br /></fieldset></td>
    </tr><tr valign="top">
    <th scope="row">Wordwrap</th>
    <td><fieldset><legend class="hidden">Wordwrap</legend><label for="wordwrap"><input name="<?php echo wp_b64_wordwrap; ?>" value="<?php echo get_option(wp_b64_wordwrap); ?>" size="40" class="code" type="text" /> How many characters per line you want</label><br /></fieldset></td>
    </tr><tr valign="top">
    <th scope="row">Block Format</th>
    <td><fieldset><legend class="hidden">Block Format</legend><label for="block_format"><select name="<?php echo wp_b64_format; ?>"><option value="bq" <?php if($b64_format=='bq'){echo 'selected';} ?>>Blockquote</option>
    <option value="cd" <?php if($b64_format=='cd'){echo 'selected';} ?>>Code</option>
    <option value="no" <?php if($b64_format=='no'){echo 'selected';} ?>>None</option></select> Choose the html format for the text block</label><br /></fieldset></td>
    </tr>
    </table>
    <h3>Editing Options</h3>
	 <table class="form-table">
	 <tr valign="top">
	 <th scope="row">Display Post Button</th>
    <td><fieldset><legend class="hidden">Display Post Button</legend><label for="display_post_button">
	 <input name="<?php echo wp_b64_button_option; ?>" type="checkbox" value="on"<?php if ($b64_button_option=='on'){ echo ' checked';} ?> />
 Hide/unhide the post button when you edit the post</label><br /></fieldset></td>
	</tr>
    </table>
<?php
  if (($oldtagcheck = wp_b64_old_tag_check()) == 1) {
  	echo '<h3>Old Tag Format</h3><p>The configuration has detected and old tag format inside your WordPress database. If you wish to update to the new tag format, BACKUP YOUR DATABASE PREVIOUSLY and then press the <em><strong>Update Database</strong></em> button.</p>';
  }
?>
    <p class="submit">
    <input type="submit" name="update" value="Update Options" />&nbsp; <input type="submit" name="reset" value="Reset Options" />
<?php
  if (($oldtagcheck = wp_b64_old_tag_check()) == 1) {
  	echo '&nbsp; <input type="submit" name="updatedb" value="Update Database" />';
  	}
?>  </p>
    </form>
  </div>
  <?php
}

?>
