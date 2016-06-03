Plugin Name: opml-blogroll widget
Description: Adds a sidebar widget to display a blogroll from an OPML URL.
Author: Camden Software Consulting
Version: 1.0
Author URI: http://www.chipstips.com/showtopic.asp?topic=phpopmlroll

This WordPress widget creates a blogroll from an OPML file that can be hosted
anywhere.

REQUIREMENTS

You must be using WordPress 2.0 (at least) and have the ability to upload
widgets.  Your host must enable allow_url_fopen (see 
http://us2.php.net/manual/en/ref.filesystem.php#ini.allow-url-fopen).
Your blogroll must be in OPML format, and reside on a host that can be accessed
publicly.

INSTALLATION

1.  Upload opml-blogroll.php to the /wp-content/plugins/widgets directory.
2.  Upload images/opml2.gif to the /wp-content/plugins/widgets/images directory
    (create the directory if necessary).
4.  In the WordPress dashboard, select "Plugins" and activate the "opml-blogroll"
    widget plugin.
5.  Now go to "Presentation/Sidebar Widgets" and open the options for "opml-blogroll".
    a.  Widget Title: set to whatever you want.  I suggest "Blogroll".
    b.  OPML URL: provide the full URL for the OPML file that will be parsed.
    c.  Exclude if no HTML link?  Check this box to omit any outline entry that
        has no "htmlUrl" or "url" attribute.
    d.  Exclude if no feed link?  Check this box to omit any outline entry that
        has no "xmlUrl" attribute.
    e.  Sort? Check this box to sort the outline using natcasesort().
6.  Drag the opml-blogroll widget to the sidebar where you want it to appear.
7.  Save changes.

OPERATION

Any entry in the outline that has no text will be automatically exluded.  To
determine the text, first the "text" attribute is queried.  If that is not found,
the "title" attribute will be used, if specified.

Any entry in the outline that has no "htmlURL", "xmlURL", or "url" attributes
will be excluded automatically, regardless of the options selected.

If an entry has an "xmlUrl" attribute, a feed icon will be displayed, with a link
to that entry's feed.

The text for the entry will be displayed.  If an "htmlUrl" or "url" attribute was
found, the text will be linked to that URL.

At the end of the list, an OPML icon is displaued, linked to your OPML file.

The entire section will be cached using WordPress' built-n caching functions for
up to 15 minutes.  However, if you "Save changes" to the widget in the dashboard,
the cache will be released immediately.

STYLING

The entries are displayed in a table (class "opml-blogroll-table").  Rows are
classed "opml-blogroll-row".  The feed icon is enclosed in a "td" element of
class "opml-blogroll-feed".  The text (an optional hyperlink) is enclode in 
a "td" element of class "opml-blogroll-html".  That should give you enough
access from your style sheet.

LIMITATIONS

1.  OPML Inclusion is not currently supported.
2.  The list does not visually represent any outline hierarchy that may exist in
    your OPML file.
3.  The "type" attribute is not consulted at all.

Send any problems or suggestions to askchip@chipstips.com
