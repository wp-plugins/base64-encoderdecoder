=== WP-Base64 Encoder/Decoder ===
Contributors: mranderson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2920389
Tags: comments, tag, encoder, decoder, base64
Requires at least: 2.0.5
Tested up to: 4.3
Stable tag: 0.9.2
License: GPL3
License URI: https://www.gnu.org/licenses/gpl.html

Enables you to encode parts of your post in base64.

== Description ==

WP-Base64 Encoder/Decoder is a Wordpress Plugin that enables you to encode parts of your post in base64. The encoded text looks like unreadable text, but when you press the "Decode" button it gets readable with inline replacement using AJAX, so it's invisible to search robots.

It uses '&#91;base64&#93;' and '&#91;/base64&#93;' tags for opening and closing the parts of post you need to encode. The tags are browser invisible, this is, if you decide to deactivate the plugin, the content enclosed by this tags will be shown like normal text in the post.

You can add the tags typing them or using the quicktag button in HTML editing mode. For now I don't provide a quicktag button in Visual editing mode, but I expect to add that in next version.

Previous versions of this plugin used '&lt;!--base64--&gt;', '&lt;!--/base64--&gt;', '&lt;base64&gt;' and '&lt;/base64&gt;' tags. You don't have to modify that posts, the new tag format has retro-compatibility. But if you wish you can update the database replacing the old tag format to the new tag format with just one click.

== Installation ==

1. Install 'base64-encoderdecoder.zip' to '/wp-content/plugins/' directory right from Wordpress Plugin Directory or unzip 'base64-encoderdecoder.zip' and upload the entire 'base64-encoderdecoder' folder to '/wp-content/plugins/' directory.

1. Activate the plugin through the 'Plugins' menu in WordPress.

1. Enclose the text you need to encrypt on your post inside '&#91;base64&#93;' and '&#91;/base64&#93;' tags using Visual or HTML Editor.

== Frequently Asked Questions ==

= Can I include html tags inside '&#91;base64&#93;' and '&#91;/base64&#93;' tags? =

Yes, you can include html tags, they will be encrypted too.

= I'm using an older version of this plugin wich uses '&lt;!--base64--&gt;', '&lt;!--/base64--&gt;', '&lt;base64&gt;' and '&lt;/base64&gt;' tags. Do I have to edit all posts and change that tags to the new ones? =

This plugin has retro-compatibility, this means that you don't have to change that old tags, they will work too. But if you wish you can update the database replacing the old tag format to the new tag format with just one click.

= I think your plugin is great. How can I thank you besides rating it? =

I thought you wouldn't ask. You can send me some bucks via PayPal, even USD$1 it's OK.

== Screenshots ==

1. This screenshot shows Base64 tags enclosing the part of the post you want to encrypt.

1. This screenshot shows how the encrypted text is shown inside the post, with the 'Decode' button at the end of the encrypted text block.

== Changelog ==

+ 0.9.2 (Sep 05, 2015)
	* Cosmetic changes to Administration page
	* Added CSS styling to Administration page
	* Added Paypal button
	* Quicktag not working, removed temporarily
	* Bugs fixed
+ 0.9.1 (Aug 10, 2015)
	* Plugin Header optimized according to WordPress Plugin Handbook
	* Internationalization according to WordPress Plugin Handbook
	* Deleted remote update function, it was redundant and innecesary now that the plugin is hosted by WordPress SVN
+ 0.9 (Jul 31, 2015)
	* Shortcode recode according to actual Codex instructions
	* Quicktag recode according to actual Codex instructions
	* Deleted legacy code no longer needed
+ 0.8.5 (Aug 01, 2009)
	* Added Internationalization file
+ 0.8.2 (Mar 13, 2009)
	* Minor bug related to remote version check fixed
	* Optimization of minor parts of the code
+ 0.8 (Mar 01, 2009)
	* Added AJAX inline text replacement
+ 0.7.1 (Feb 25, 2009)
	* Fixed a bug related to multiple base64 blocks showing on different posts at the same time
+ 0.7 (Feb 03, 2009)
	* Database update function from old tags to new ones
+ 0.6.1 (Feb 02, 2009)
	* Fixed some bugs related to double quotes inside a base64 block
	* Deleted redundant and unnecesary code
+ 0.6 (Jan 30, 2009)
	* Inline replacement, no need for different flavors
	* Removed post title variable because of inline replacement
	* New tag format html-styled with retro-compatibility
	* Revamped configuration page
+ 0.4.2 (Jul 16, 2007)
	* Minor quicktag bug fixed
+ 0.4 (Nov 13, 2006)
	* Added checking for new versions
	* Added Quicktags button and configurable activation/deactivation
+ 0.3 (Oct 26, 2006)
	* Added options screen
	* Configurable wordwrap, text block html formatting, new post title and submit button text
+ 0.2 (Oct 23, 2006)
	* Multiple base64 encoded blocks
	* Optimized checking for paired tag formatting
+ 0.1 (Oct 21, 2006)
	* First release
	
== Upgrade Notice ==

+ 0.9.2 (Sep 05, 2015)
	* Minor upgrade to comply with current WordPress standards
	
== Credits ==

Most parts of the code are not my creation, they were borrowed from people smarter than me, so I must thank to them.

Thanks to [aNieto2k's AntiTroll Plugin](http://www.anieto2k.com/2006/02/08/plugin-antitroll/) for part of the code, because that was my first source when I knew nothing about creating a Wordpress Plugin.

Thanks to [Random Snippets](http://www.randomsnippets.com/2008/03/07/how-to-find-and-replace-text-dynamically-via-javascript/) for the Javascript replacement script. Without it the plugin will be unable to replace text inline.

Thanks to [Lorelle's Blog](http://lorelle.wordpress.com/2005/12/01/search-and-replace-in-wordpress-mysql-database/) for the info on how to search and replace inside a Wordpress database.

Thanks to [MyDigitalLife](http://www.mydigitallife.info/2006/06/24/retrieve-and-get-wordpress-post-id-outside-the-loop-as-php-variable/) for the info on how to identify the postID, helping me to solve the bug related to multiple base64 blocks showing on different posts at same time.

Thanks to [Daniel Lorch](http://daniel.lorch.cc/docs/ajax_simple/) for the info on how to use AJAX inside the plugin, it was a clarificating example.

Thanks to [Automatic Timezone Plugin](http://wordpress.org/extend/plugins/automatic-timezone/) for parts of script that adds "Settings" link to Admin Page in Installed Plugins Page.

Thanks to [Famfamfam](http://www.famfamfam.com/lab/icons/silk/) for the key icon used for the Admin page.
