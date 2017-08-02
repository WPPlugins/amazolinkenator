=== Amazo Linkenator ===
Contributors: Rick Hellewell
Donate link: http://cellarweb.com/wordpress-plugins/
Tags: amazon affiliate links shorten urls
Requires at least: 4.0.1
Tested up to: 4.8
Version: 2.10
Stable tag: 2.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically adds your Amazon Affiliate code to any Amazon product URLs. For posts, pages, & comments. Optionally shortens URLs - See More Details!
 
== Description ==

AmazoLinkenator will automatically (without any effort on your part) add your Amazon Affiliate code to any Amazon URLs. This will happen for pages, posts, and comments (depending on your settings below). Because you don't have to do anything special to affiliate a URL, this plugin is great for sites with lots of authors.

No extra steps are needed to add your affiliate code. Just paste the Amazon link in the page/post/comment, and Publish. Your Amazon Affiliate link will automatically be added to the product URL. You can use different Affiliate codes at any time; the new code will only affect newly saved content.

And AmazoLinkenator also works with any Amazon product links that your site commenters or authors might add. If anyone includes a link in their comment that has their Amazon Affiliate code, it will be replaced with your affiliate code. All automatically! A counter on the Settings screen keeps track of Affiliate code insertions.

URLs are affiliated only when posts/pages/comments are saved/updated/submitted. Any prior content will use the current affiliate code if it is re-saved (unless you have enabled the URL Shortener option.)

All you need is a valid Amazon Affiliate Key. The plugin doesn't check for a valid Amazon Affiliate key. Any URL affiliated will not be re-affiliated, and will stay the same, unless you update (re-publish) the content.

Plus, there's an option to automatically shorten the URL. This is great for 'hiding' your Amazon Affilliate link code. You just need a free Bit.ly Generic Access Token from https://affiliate-program.amazon.com/gp/associates/network/main.html . A Validate button is available to check for a valid Bit.ly API key.

Get your Bit.ly Generic Access Token from https://bitly.com/a/create_oauth_app .



== Installation ==

This section describes how to install the plugin and get it working.

1. Download the zip file, uncompress, then upload to `/wp-content/plugins/` directory. Or install via the Add Plugin page.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Change settings in Settings, 'AmazoLinkenator Settings' to your requirements.

* Note: do a "Save" on the AmazoLinkenator Settings page once after an upgrade to ensure all is well; your settings will be preserved.

== Frequently Asked Questions ==

= Do I have to do anything special to enable this? =

You need to have your own Amazon Affiliate code, and set up the two options on the Settings page. If you want to automatically shorten URLs, you'll need a Bit.ly 'Generic Access Token'. Links to get those items are on the Settings page. And there is a Validate button to ensure you have a good Bit.ly API key.

= What settings are available? =

Just a few: your Amazon Affiliate code, and checkboxes to enable for posts/pages or comments. Optionally, you can also have the URLs shortened (you'll need a Bit.ly 'Generic Access Token' key).

= What if there is already an affiliate code in the Amazon URL? =

An existing Affiliate code in any Amazon URL is replaced with the one that you specify. Once the post/page/comment is (re-)saved, your Affiliate code is inserted. It will be changed to the current Affiliate code on a subsequent save. If you have enabled the URL shortener (optional), any existing amzn.to will be re-affiliated with your Affiliate code.

= What if I don't like how the plugin changes things or there is a problem? = 

You can just deactivate the plugin. Your settings will be saved if you want to reactivate later.

= Does the plugin make changes to the database? = 

The plugin only adds one 'row' to the Options database, using standard WordPress functions. The plugin will read the values as needed, minimizing calls to the database to limit any overhead against the database.

= Does the plugin require anything extra on the client (visitor) browser? = 

Not that we are aware of. Everything follows WordPress coding standards. All the user will see are the  modified links.

= Where can we go for support if there is a problem or question - or a new feature we think will be nifty? = 

You can use the plugin support page for questions. Or you can contact us directly via the Contact Us page at www.CellarWeb.com . We usually respond within 24 hours (and are usually faster than that).

= How much does the plugin cost? = 

It's free! But you can donate by letting the plugin use our Amazon Affiliate code, which will happen every 100 links. That's optional, but appreciated!

= What else do you do? = 

We have a plugin that stops comment spam in it's tracks. Plus a contact template that does the same thing. Our spam-blocking process doesn't rely on things that don't work, like hidden fields, or hard to use Captcha things. You'll find all the details at our FormSpammerTrap site: http://www.FormSpammerTrap.com .

A new plugin - URL Smasher - will shorten all links in your content when saved. All you need is a Bit.ly token, which is free. 
We do lots of WordPress sites: implementation, customization, and more. You can find more info at our business site at http://www.CellarWeb.com .


== Screenshots ==

1. Shows the AmazoLinkenator Settings screen, found on the Settings, 'AmazoLinkenator Settings' screen. (assets/screenshot-01.jpg) (You'll have to enter your Amazon Affiliate code, and optionally your Bit.ly Generic Access Token key. Links to get those are on the Settings screen.)

== Changelog ==

= 2.10 (1 June 2017) = 
* fixed issue with the Test API Key button
* tested for WP version 4.8

= 2.01 (28 Jan 2017) = 
* added button to test the Bit.ly Access Token
* changed Bit.ly Access Token info text (next to the field) to alert about the Validate Bit.ly Access Token button
* some function name changes for consistant pattern
* minor code efficiencies
* correction to prior version changelog/update in readme
* changes to text on the Settings screen to add information about the Validate Bit.ly API Key test button.
* with the Validate button implemented, jumped from version 1.14 to 2.01.

= 1.14 (27 Jan 2017) =
* tested for WP 4.7.2
* changes to banner on settings page to match other http://www.cellarweb.com plugins
* added H2 placeholder for WP Admin messages above the banner, so they don't overwrite settings area
* moved version number to header; no longer a saved setting
* other textual changes to the Settings page
* minor code efficiencies, including some debug and unused code

= 1.13 (10 Jan 2017) = 
* some minor code efficienies

= 1.12 (9 Jan 2017) =
* changed the regex used for extracting URLs from the text to work with German ulauts.

= 1.11 (4 Jan 2017) =
* added support for all Amazon links, not just .com

= 1.10a (1 Aug 2016) = 
* fixed minor issue with links at end of post/comment not getting affiliate code, or shortened. 
* minor changes to the information on the Settings screen, and the readme file.

= 1.10 (31 Jul 2016) = 
* added ability to uncompress amzn.to links to ensure that the proper affiliate code is used
* changed and optimized the Affiliate-code-adding section to be more efficient and use WordPress function where available
* ensures all links are 'clickable' by adding the HREF to the text (works on all links)
* some other code optimizations
* if there is a duplicate 'tag' code for some reason, it will be removed, leaving only the 'tag' code with your Affiliate code (this didn't always work consistently)
* ensured that any 'target' parameters are retained in the URL
* prevent XSS attacks by santizing all Affiliated URLs (using esc_url_raw() function)
* better/slightly faster way of updating the counters on the Settings screen
* minor formatting/style/visual changes to Settings screen

= 1.02 (xx Dec 2015) =
* internal testing version; never released (sometimes when you make changes that don't work, it is better to reboot from the last version and try again)

= 1.01 (16 Dec 2015) = 
* Don't you hate it when a bug creeps in after you do all the testing and then release a revision and then things don't work right. Yeah, me too. (sigh)
* Fixed a bug where not all post content would be affiliated. And then tested everything again.

= 1.00 =
* Initial release (15 Dec 2015)
* Testing to ensure proper behavior with other plugins, like our (shameless plug) "URL Smasher", which automatically smashes and shortens URLs

= 0.9x =
* internal testing versions, fixed various bug fixes during testing, added features, etc.

== Upgrade Notice ==

= 2.01 (28 Jan 2017) = 
* added button to test the Bit.ly Access Token
* changed Bit.ly Access Token info text (next to the field) to alert about the Validate Bit.ly Access Token button
* some function name changes for consistant pattern
* minor code efficiencies
* correction to prior version changelog/update in readme
* changes to text on the Settings screen to add information about the Validate Bit.ly API Key test button.
* with the Validate button implemented, jumped from version 1.14 to 2.01.

= 1.14 (27 Jan 2017) =
* tested for WP 4.7.2
* changes to banner on settings page to match other http://www.cellarweb.com plugins
* added H2 placeholder for WP Admin messages above the banner, so they don't overwrite settings area
* moved version number to header; no longer a saved setting
* other textual changes to the Settings page
* minor code efficiencies, including some debug and unused code

= 1.13 (10 Jan 2017) = 
* some minor code efficienies

= 1.12 (9 Jan 2017) =
* changed the regex used for extracting URLs from the text to work with German ulauts.

= 1.11 (4 Jan 2017) =
* added support for all Amazon links, not just .com

= 1.10a (1 Aug 2016) = 
* fixed minor issue with links at end of post/comment not getting affiliate code, or shortened. 
* minor changes to the information on the Settings screen, and the readme file.

= 1.10 (31 Jul 2016) = 
* added ability to uncompress amzn.to links to ensure that the proper affiliate code is used
* changed and optimized the Affiliate-code-adding section to be more efficient and use WordPress function where available
* ensures all links are 'clickable' by adding the HREF to the text (works on all links)
* some other code optimizations
* if there is a duplicate 'tag' code for some reason, it will be removed, leaving only the 'tag' code with your Affiliate code (this didn't always work consistently)
* ensured that any 'target' parameters are retained in the URL
* prevent XSS attacks by santizing all Affiliated URLs (using esc_url_raw() function)
* better/slightly faster way of updating the counters on the Settings screen
* minor formatting/style/visual changes to Settings screen

= 1.02 (xx Dec 2015) =
* internal testing version; never released (sometimes when you make changes that don't work, it is better to reboot from the last version and try again)

= 1.01 (16 Dec 2015) = 
* Don't you hate it when a bug creeps in after you do all the testing and then release a revision and then things don't work right. Yeah, me too. (sigh)
* Fixed a bug where not all post content would be affiliated. And then tested everything again.

= 1.00 =
* Initial release (15 Dec 2015)


