<?php

  $updated_fade_in = '<div id="message" class="updated fade b64-updated-fade"><p><strong>';
  $updated_fade_out = '</strong></p></div>';
  if (isset($_POST['update'])) {
    update_option('b64_wordwrap', $_POST['b64_wordwrap']);
    update_option('b64_format', $_POST['b64_format']);
    update_option('b64_button_text', $_POST['b64_button_text']);
    update_option('b64_button_option', $_POST['b64_button_option']);
    echo $updated_fade_in . __( 'Options Updated', 'base64-encoderdecoder') . $updated_fade_out;
  }
  if (isset($_POST['reset'])) {
    update_option('b64_wordwrap', $defaults['b64_wordwrap']);
    update_option('b64_format', $defaults['b64_format']);
    update_option('b64_button_text', $defaults['b64_button_text']);
    update_option('b64_button_option', $defaults['b64_button_option']);
    echo $updated_fade_in . __( 'Options Reseted', 'base64-encoderdecoder') . $updated_fade_out;
  }
  if (isset($_POST['updatedb'])) {
	b64_update_db();
	echo $updated_fade_in . __('Database updated to new tag format', 'base64-encoderdecoder') . $updated_fade_out;
  }
  
echo "<div class=\"wrap b64-wrap\">";
echo "<div id=\"b64-options-panel\">";
echo "<h2 class=\"b64-head\">".__('Base64 Encoder/Decoder', 'base64-encoderdecoder')."</h2>";
echo "<p id=\"b64-header-credit\">".__('WordPress Plugin developed by', 'base64-encoderdecoder')." <em><a href=\"https://twitter.com/mrandersonmd\">&#64;MrAndersonMD</a></em></p>";
echo "<p id=\"b64-notification\">".__('This is version', 'base64-encoderdecoder')." <span class=\"b64-version-num\">".b64_plugin_version()."</span>. <a href=\"https://wordpress.org/plugins/base64-encoderdecoder/faq/\">FAQ</a> | <a href=\"https://wordpress.org/support/plugin/base64-encoderdecoder\">".__('Support', 'base64-encoderdecoder')."</a></p>";
echo "<h3 class=\"b64-subhead-visualization\">".__('Display Options', 'base64-encoderdecoder')."</h3>";
echo "<form name=\"b64_options\" method=\"post\">";

settings_fields( 'base64-encoderdecoder' );
do_settings_sections( 'base64-encoderdecoder' );

echo "<table class=\"form-table b64-options-table\"><tr>";
echo "<th scope=\"row\">".__('Button Text', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Button Text', 'base64-encoderdecoder')."</legend><label for=\"button_text\"><input name=\"" . b64_button_text . "\" value=\"" . get_option(b64_button_text) . "\" size=\"20\" class=\"code\" type=\"text\" /> <span class=\"b64-extra-info\">";
echo __('Text for the submit button', 'base64-encoderdecoder')."</span></label><br /></fieldset></td>";
echo "</tr><tr>";
echo "<th scope=\"row\">".__('Wordwrap', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Wordwrap', 'base64-encoderdecoder')."</legend><label for=\"wordwrap\"><input name=\"" . b64_wordwrap . "\" value=\"" . get_option(b64_wordwrap) . "\" size=\"20\" class=\"code\" type=\"text\" /> <span class=\"b64-extra-info\">";
echo __('How many characters per line you want', 'base64-encoderdecoder')."</span></label><br /></fieldset></td>";
echo "</tr><tr>";
echo "<th scope=\"row\">".__('Block Format', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Block Format', 'base64-encoderdecoder')."</legend><label for=\"block_format\"><select name=\"" . b64_format . "\"><option value=\"bq\"";
if(get_option(b64_format)=='bq'){echo ' selected';}
echo ">".__('Blockquote', 'base64-encoderdecoder')."</option>";
echo "<option value=\"cd\"";
if(get_option(b64_format)=='cd'){echo ' selected';}
echo ">".__('Code', 'base64-encoderdecoder')."</option>";
echo "<option value=\"no\"";
if(get_option(b64_format)=='no'){echo ' selected';}
echo ">".__('None', 'base64-encoderdecoder')."</option></select> <span class=\"b64-extra-info\">";
echo __('Choose the html format for the text block', 'base64-encoderdecoder')."</span></label><br /></fieldset></td>";
echo "</tr></table>";

echo "<h3 class=\"b64-subhead-edition\">".__('Editing Options', 'base64-encoderdecoder')."</h3>";
echo "<table class=\"form-table b64-options-table\"><tr>";
echo "<th scope=\"row\">".__('Display Post Button', 'base64-encoderdecoder')."</th>";
echo "<td><fieldset><legend class=\"hidden\">".__('Display Post Button', 'base64-encoderdecoder')."</legend><label for=\"display_post_button\">";
echo "<input name=\"" . b64_button_option . "\" type=\"checkbox\" value=\"on\"";
if (get_option(b64_button_option)=='on'){ echo ' checked';}
echo " /> <span class=\"b64-extra-info\">".__('Hide the shortcode button when editing the post', 'base64-encoderdecoder')."</span></label><br /></fieldset></td>";
echo "</tr></table>";

  if (($oldtagcheck = b64_old_tag_check()) == 1) {
  	echo "<h3 class=\"b64-subhead-oldtag\">".__('Old Tag Format', 'base64-encoderdecoder')."</h3>";
	echo "<p>".__('The configuration has detected and old tag format inside your WordPress database. If you wish to update to the new tag format, BACKUP YOUR DATABASE PREVIOUSLY and then press the <em><strong>Update Database</strong></em> button.', 'base64-encoderdecoder')."</p>";
  }
echo "<p class=\"submit\">";
echo "<input type=\"submit\" name=\"update\" value=\"".__('Update Options', 'base64-encoderdecoder')."\" class=\"button-primary\" />&nbsp;&nbsp;";
echo "<input type=\"submit\" name=\"reset\" value=\"".__('Reset Options', 'base64-encoderdecoder')."\" class=\"button-secondary\" />";
  if (($oldtagcheck = b64_old_tag_check()) == 1) {
  	echo "&nbsp;&nbsp;<input type=\"submit\" name=\"updatedb\" value=\"".__('Update Database', 'base64-encoderdecoder')."\" class=\"button-secondary\" />";
  	}
echo "</p></form></div>";

echo "<div id=\"b64-paypal\">";
echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\">";
echo "<p>If you find this plugin useful, please consider donating a small amount through Paypal<br/><br/>";
echo "<select name=\"amount\">";
echo "<option value=\"1.00\">$1.00 USD</option>";
echo "<option value=\"2.00\">$2.00 USD</option>";
echo "<option value=\"5.00\">$5.00 USD</option>";
echo "</select><br/><br/>";
echo "<input type=\"hidden\" name=\"cmd\" value=\"_donations\">";
echo "<input type=\"hidden\" name=\"business\" value=\"5TDZ7KLJFS4QU\">";
echo "<input type=\"hidden\" name=\"item_name\" value=\"Base64 Encoder/Decoder (WordPress)\">";
echo "<input type=\"hidden\" name=\"currency_code\" value=\"USD\">";
echo "<input type=\"hidden\" name=\"no_note\" value=\"0\">";
echo "<input type=\"hidden\" name=\"cn\" value=\"Base64 Encoder/Decoder\">";
echo "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">";
echo "<input type=\"hidden\" name=\"rm\" value=\"1\">";
echo "<input type=\"hidden\" name=\"bn\" value=\"PP-DonationsBF:btn_donateCC_LG.gif:NonHosted\">";
echo "<input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">";
echo "<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">";
echo "</p></form></div>";

echo "</div>";

?>