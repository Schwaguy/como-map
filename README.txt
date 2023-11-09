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

== Installation ==
1. Upload `como-map.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `[comomap maps=# OF MAPS maptype=STATIC/DYNAMIC mapid=MAP_ELEMENT_ID width=WIDTH height=HEIGHT labelclass=LABEL_CLASS label=LABEL address=STREET_ADDRESS lat=Latitude long=LONGITUDE centerlat=CENTER_LATITUDE centerlong=CENTER_LONGITUDE googlelink=GOOGLE_LINK binglink=GOOGLE_LINK content=INFO_BOX_CONTENT phone=PHONE countrycode=COUNTRY_CODE zoom=ZOOM style=1-10 icon=icon animate=TRUE/FALSE markercolor=COLOR showinfo=FALSE showon=click/hover]` shortcode in your templates

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
1.2.5 - Removed errant Google API Key
