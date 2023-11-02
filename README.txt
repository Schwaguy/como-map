=== Plugin Name ===
Contributors: Como Creative LLC
Tags: Google Map
Requires at least: 5.0.0
Tested up to: 5.3.2
Stable tag: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Plugin to enable embedding custom Google Maps
== Description ==
Plugin to enable embedding custom Google Maps
A few notes about the sections above:
*   "Contributors" is a comma separated list of wp.org/wp-plugins.org usernames
*   "Tags" is a comma separated list of tags that apply to the plugin
*   "Requires at least" is the lowest version that the plugin will work on
*   "Tested up to" is the highest version that you've *successfully used to test the plugin*. Note that it might work on
higher versions... this is just the highest one you've verified.
*   Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.
    Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin.  In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer.  Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.
    If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.
== Installation ==
This section describes how to install the plugin and get it working.
e.g.
1. Upload `como-map.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('Como_Map_hook'); ?>` in your templates
== Frequently Asked Questions ==
= A question that someone might have =
An answer to that question.
= What about foo bar? =
Answer to foo bar dilemma.
== Screenshots ==
1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot
== Changelog ==
= 1.0 =
1.1.0 - Added ability to specify different zoom levels when multiple maps appear on page
1.1.1 - Minor Widget Error Fix
1.1.2 - Added ability to show map Info Box by default
1.1.3 - Fixed Chrome map loading issue on initial page visit
1.1.4 - Fixed php errors for multi-marker map shortcode
1.1.5 - Fixed asynchronous script loading issue
1.1.6 - Fixed initial not defined issue for multiple maps on a page
1.1.7 - Removed async defer from Google Map script to fix Chrome loading issue
1.1.8 - Added ability to disable Map Pin animation with  shortcode attribute "animate=TRUE/FALSE"
1.1.9 - Added abolity to show Map Info Box on hover
1.2.0 - Added text strings for "Get Directions" links
1.2.1 - Added option to include or exclude "async defer" from Google Maps script call
1.2.2 - Added Ultra Lite Grey style template
1.2.3 - Added ability to override Auto-Center when showing Info Box
1.2.4 - Added formatPhoneLink() function so plugin can be used separate from Como Themes
* A change since the previous version.
* Another change.
= 0.5 =
* List versions from most recent at top to oldest at bottom.
== Upgrade Notice ==
= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.
= 0.5 =
This version fixes a security related bug.  Upgrade immediately.
== Arbitrary section ==
You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.
== A brief Markdown Example ==
Ordered list:
1. Some feature
1. Another feature
1. Something else about the plugin
Unordered list:
* something
* something else
* third thing
Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.
[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"
Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.
`<?php code(); // goes in backticks ?>`