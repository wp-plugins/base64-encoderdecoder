=== WP-Base64 Encoder/Decoder ===
Contributors: mranderson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2920389
Tags: comments, tag, encoder, decoder, base64
Requires at least: 2.1
Tested up to: 2.7.1
Stable tag: 0.71

Enables you to encode parts of your post in base64.

== Description ==

WP-Base64 Encoder/Decoder is a Wordpress Plugin that enables you to encode parts of your post in base64. The encoded text looks like unreadable text, but when you press the "Decode" button it gets readable with inline replacement.

It uses html-styled `<base64>` and `</base64>` tags for opening and closing the parts of post you need to encode. The tags are browser invisible, this is, if you decide to deactivate the plugin, the content enclosed by this tags will be shown like normal text in the post.

You can add the tags typing them or using the quicktag button in HTML editing mode. For now I don't provide a quicktag button in Visual editing mode, but I expect to add that in next version.

Previous versions of this plugin used `<!--base64-->` and `<!--/base64-->` tags. You don't have to modify that posts, the new tag format has retro-compatibility. But if you wish you can update the database replacing the old tag format to the new tag format with just one click.

== Installation ==

1. Install `base64-encoderdecoder.0.71.zip` to `/wp-content/plugins/` directory right from Wordpress Plugin Directory or unzip `base64-encoderdecoder.0.71.zip` and upload the entire `base64-encoderdecoder` folder to `/wp-content/plugins/` directory.

1. Activate the plugin through the 'Plugins' menu in WordPress.

1. Enclose the text you need to encrypt on your post inside `<base64>` and `</base64>` tags.

1. Be sure to use the HTML Editor, because Rich Text Editor deletes all html-like tags that are not standard html tags. I will solve this in the next version.

== Frequently Asked Questions ==

= Can I include html tags inside '&lt;base64&gt;' and '&lt;/base64&gt;' tags? =

Yes, you can include html tags, they will be encrypted too.

= I'm using an older version of this plugin wich uses '&lt;!--base64--&gt;' and '&lt;!--/base64--&gt;' tags. Do I have to edit all posts and change that tags to the new ones? =

This plugin has retro-compatibility, this means that you don't have to change that old tags, they will work too. But if you wish you can update the database replacing the old tag format to the new tag format with just one click.

= Why you don't provide a Visual editor quicktag button for the plugin? =

That's because I haven't learned yet how to add it, but I'm working on that.

= When I switch from HTML Editor to Rich Text Editor and back again to HTML Editor, Base64 tags are deleted automatically. Why is this? =

Rich Text Editor deletes all html-like tags that are not standard html tags. It will be solved when the Visual quicktag button is implemented.

= I think your plugin is great. How can I thank you besides rating it? =

I thought you wouldn't ask. You can send me some bucks via PayPal, even USD$1 it's OK.

== Screenshots ==

1. This screenshot shows Base64 tags enclosing the part of the post you want to encrypt.

1. This screenshot shows how the encrypted text is shown inside the post, with the `Decode` button at the end of the encrypted text block.

== Credits ==

Most parts of the code are not my creation, they were borrowed from people smarter than me, so I must thank to them.

Thanks to [aNieto2k's AntiTroll Plugin](http://www.anieto2k.com/2006/02/08/plugin-antitroll/) for part of the code, because that was my first source when I knew nothing about creating a Wordpress Plugin.

Thanks to [Random Snippets](http://www.randomsnippets.com/2008/03/07/how-to-find-and-replace-text-dynamically-via-javascript/) for the Javascript replacement script. Without it the plugin will be unable to replace text inline.

Thanks to [Lorelle's Blog](http://lorelle.wordpress.com/2005/12/01/search-and-replace-in-wordpress-mysql-da tabase/) for the info on how to search and replace inside a Wordpress database.

Thanks to [MyDigitalLife](http://www.mydigitallife.info/2006/06/24/retrieve-and-get-wordpress-post-id-outside-the-loop-as-php-variable/) for the info on how to identify the postID, helping me to solve the bug related to multiple base64 blocks showing on different posts at same time.

