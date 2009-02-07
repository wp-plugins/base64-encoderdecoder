=== WP-Base64 Encoder/Decoder ===
Contributors: mranderson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2920389
Tags: comments, tag, encoder, decoder, base64
Requires at least: 2.1
Tested up to: 2.7
Stable tag: 0.6

Enables you to encode parts of your post in base64.

== Description ==

WP-Base64 Encoder/Decoder is a Wordpress Plugin that enables you to encode parts of your post in base64. The encoded text looks like unreadable text, but when you press the "Decode" button it gets readable with inline replacement.

It uses html-styled `<base64>` and `</base64>` tags for opening and closing the parts of post you need to encode. The tags are browser invisible, this is, if you decide to deactivate the plugin, the content enclosed by this tags will be shown like normal text in the post.

You can add the tags typing them or using the quicktag button in HTML editing mode. For now I don't provide a quicktag button in Visual editing mode, but I expect to add that in next version.

Previous versions of this plugin used `<!--base64-->` and `<!--/base64-->` tags. You don't have to modify that posts, the new tag format has retro-compatibility. In next version of the plugin I expect to provide a manual one-button replacement from old tags to new tags.

== Installation ==

1. Install `wp-base64.zip` to `/wp-content/plugins/` directory right from Wordpress Plugin Directory or unzip `wp-base64.zip` and upload the entire `wp-base64` folder to `/wp-content/plugins/` directory.

1. Activate the plugin through the 'Plugins' menu in WordPress.

1. Enclose the text you need to encrypt on your post inside `<base64>` and `</base64>` tags.

== Frequently Asked Questions ==

= Can I include html tags inside '<base64>' and '</base64>' tags? =

Yes, you can include html tags, they will be encrypted too. Just don't use double quotes, use single quotes if needed.

For example, if you plan to include `<a href="http://www.wordpress.org">Wordpress</a>` you have to type it with single quotes like this `<a href='http://www.wordpress.org'>Wordpress</a>`. I don't know why double quotes doesn't work, but sinle ones do the job.

= I'm using an older version of this plugin wich uses '<!--base64-->' and '<!--/base64-->' tags. Do I have to edit all posts and change that tags to the new ones? =

This plugin has retro-compatibility, this means that you don't have to change that old tags, they will work too.

= Why you don't provide a Visual editor quicktag button for the plugin? =

That's because I haven't learned yet how to add it, but I'm working on that.

= I think your plugin is great. How can I thank you besides rating it? =

I thought you wouldn't ask. You can send me some bucks via PayPal, even USD$1 it's OK.

== Screenshots ==

![Base64 Plugin Screenshot](/tags/0.6/plugin-base64.jpg)
Base64 screenshot showing how the tag works in editor mode and how it's seen when reading it.

== Credits ==

Most parts of the code are not my creation, they were borrowed from people smarter than me, so I must thank to them.

Thanks to [Random Snippets](http://www.randomsnippets.com/2008/03/07/how-to-find-and-replace-text-dynamically-via-javascript/) for the Javascript replacement script. Without it the plugin will be unable to replace text inline.

Thanks to [aNieto2k's AntiTroll Plugin](http://www.anieto2k.com/2006/02/08/plugin-antitroll/) for part of the code, because that was my first source when I knew nothing about creating a Wordpress Plugin.
