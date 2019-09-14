=== OS OpenSpace Maps ===
Contributors: skirridsystems, jonlynch
Tags: Map, Maps, Ordnance Survey, OpenSpace, Walking, GPS, KML, GPX, Routes
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZFCAZLGF7UFYS
Requires PHP: 5.2.4
Requires at least: 3.5
Tested up to: 5.0
Stable tag: 1.3.1

A plugin to display UK Ordnance Survey maps with markers and tracks.

== Description ==
A plugin to display UK Ordnance Survey [OpenSpace](https://openspace.ordnancesurvey.co.uk/openspace/) maps with markers and tracks.

This plugin works using the `[osmap]` shortcode anywhere in your post, page or custom post type content. It allows maps with markers or GPX/KML files to be shown. Examples are available at [ButNoIdea.co.uk](http://www.butnoidea.co.uk) and [mudandroutes](https://www.mudandroutes.com/the-snowdon-horseshoe-classic-scrambling-route/).

The following attributes can be passed:

* `height="600"` sets the height of the map div
* `width="800"` sets the width of the map div. Leave blank to use available width.
* `scale="5"` sets the initial zoom level
* `color="red"` or `color="#ff0000"` (`colour` is also acceptable) sets the colour of the track shown from a file.
* `track="3"` sets the width of the track shown from a file.
* `gpx="http://www.example.com/myfile.gpx"` specifies a gpx file to be displayed
* `kml="http://www.example.com/myfile.kml"` specifies a kml file to be displayed
* `markers="NY1234512345;Marker 1|54.454198,-3.211527;Marker 2"` a list of positions and (optionally) labels separated by `|` with a `;` between the position and label. Positions can be 6, 8 or 10 figure grid references or decimal lat long. Labels, if present, must be simple text.
* `markerfile="http://example.com/markers.txt"` specifies a text file containing marker definitions, one per line. As above, position and label are separated by `;`. In this case the label can include any text including HTML, should you wish to include links or other formatting.
* `centre="54.454198,-3.211527"` sets the map centre point, to override the default centre or if not using markers or track files

Scale uses the OS scale values which are essentially multipliers on 1:10000 scale, so 5 represents 1:50000 and 100 represents 1:1M. The available scale values are 1, 2, 2.5, 4, 5, 10, 20, 50, 100, 200, 500, 1000, 2500. These are also listed on the help tab when you are editing a post or page.

To upload a KML or GPX file just use the WordPress media uploader and then copy the address of the uploaded file into the shortcode.

= Examples =
* `[osmap height="300" width="300" color="blue" gpx="http://www.example.co.uk/myfile.gpx"]` displays a 300px by 300px window containing a blue track from the file specified.

* `[osmap markers="NY2000008000;Wasdale"]`shows a default size and zoom window with a marker placed and the popup text "Wasdale"

== Installation ==

1. Go to the Plugins page in the admin area, search for `openspace` navigate to the plugin page and click `install`.
1. Or download and extract `os-openspace-maps.zip` into the `/wp-content/plugins/` directory. This will create a subfolder called `os-openspace-maps` containing the plugin files.
1. Activate `OS OpenSpace Maps` through the 'Plugins' menu in WordPress
1. Before the plugin will work you need to add your API key to the settings page on the dashboard.
1. If you are running WordPress Multisite you (or your network administrator) will have to add KML and GPX files to the allowed file types under 'Network Settings' if you wish to upload these files.

== Frequently Asked Questions ==

= Where can I get support? =

You can find more information on the [plugin homepage](https://skirridsystems.co.uk/wordpress-plugins/os-openspace-maps/)

Ask questions in the support forum on the [WordPress plugin page](https://wordpress.org/plugins/os-openspace-maps/)

== Screenshots ==

1. A map embedded in a WordPress post
2. The settings page

== Changelog ==
= 1.3.1 =
* Fix order of initialisation which was causing a crash on update

= 1.3.0 =
* Admin option to disable mousewheel zoom
* Admin option to add GPX file download link
* Option for GPX track width
* Improved selection of map scale in shortcode attributes
* Can define multiple markers in a text file
* Can create map with neither markers nor GPX track
* Works with http:// and https:// sites
* Security problems addressed and settings validated
* Fix incorrect map centring when using only lat/long coordinates
* Improved help text

= 1.2.3 =
* CSS fix for stripy map tiles after the OS changed OpenSpace

= 1.2.2 =
* Fixed problem with WordPress versions earlier than 3.3
* Added hooks and filters to allow plugins to modify output
* Added proxy for GPX and KML files not on the wordpress host

= 1.2.1 = 
* Bug Fix

= 1.2 =
* Multiple markers with auto zoom
* API key checking
* New help tabs added to options and post editing screens
* Add multiple maps to post or pages
* Able to use lat long for markers

= 1.1.1 =
* Bug Fix

= 1.1 =
* Only adds one row to the options table
* Fixed feed bug

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.3.1 =
* Fix crash on update

= 1.3.0 =
* Improved security and settings validation
* Works with https sites
* GPX and KML files must reside on WordPress server
* Requires WordPress 3.5

= 1.2.2 =
* Fixed bug with WordPress versions earlier than 3.3
* Added hooks and filters to allow plugins to modify output
* Added proxy for GPX and KML files not on the wordpress host

= 1.2.1 =
* Critical Bug Fix (Sorry!)

= 1.2 =
* New Features
* More Help

= 1.1 =
* Bug Fix

= 1.0 =
* Fixes feed bug
