=== OWMS for WordPress ===
Contributors: marklindhout
Donate link: http://langdradig.nl
Tags: owms, metadata, dublin core, meta
Requires at least: 2.8
Tested up to: 3.2
Stable tag: 0.1.5

Inserts OWMS metadata as meta tags into all post types. Provides options screen in admin area for setting default values.

== Description ==

OWMS for Wordpress is a plugin that inserts meta data into the displayed posts. It is a meta data standard developed for the meta enrichment of Dutch government information. The standard is currently at 3.5, and a proposal for OWMS 4.0 is currently under consideration. 

**Features**

* Easy – Just upload and activate the plugin.
* Default selection - You can select defaults that apply to each post. These are then auto-filled when you create a post, so it saves time!

**Credit** 
This plugin is based on the excellent [Dublin Core for WordPress](http://wordpress.org/extend/plugins/dublin-core-for-wp/) by [phyzome](http://profiles.wordpress.org/users/phyzome/) and [jjunyent](http://profiles.wordpress.org/users/jjunyent/).

== Installation ==

1. Decompress the .zip archive and upload the `wp-owms` folder file into `/wp-content/plugins/` on your server.
 * Alternatively, you can search for `OWMS` in your WordPress plugin installer at Plugins > Add New.
1. Activate the plugin through the `Plugins` menu in WordPress
1. Open the OWMS settings page to customize the default values.

== Frequently Asked Questions ==

= How to enter default values? =

In the WordPress back-end, under `Settings > OWMS`, you will find a selection of fields which will provide defaults on creating a new post. In short: If you fill something in here, all new posts will have that value.

== Screenshots ==

1. OWMS Settings page in the administration backend
2. The location of the OWMS settings page in the administration menu

== Changelog ==

= 0.1.4 =
* Actually added screenshots now :P

= 0.1.3 =
* Cleaned up PHP notices that were thrown due to sloppy array looping functions
* Improved access to the terms inside functions through use of 'global'.
* Corrected HTML output syntax

= 0.1.2 =
* Added descriptions of the plugin and installation instructions.
* Added plugin screenshots.

= 0.1.1 =
* Added translation files
* Added Dutch translation (nl_NL) files.

= 0.1.0 =
* Initial release
