WP Multiple Subtitles
===========

Add subtitles (subheadings) to posts or custom post types.

Subtitles are effective way of attracting audience and search engines. The 'WP Multiple Subtitles' plugin helps you to have subtitles of your posts and pages. These sub-headings are shown below the title of posts in short lines in SEO friendly way. It can be activated with any of your template..

`<?php the_subtitles(); ?>` is used for inside The Loop. If you wish to get a page/post's subtitle outside The Loop, use `<?php get_the_subtitles( $post ); ?>`, where $post is a post object or ID ($post->ID).

### Parameters

Just like WP's built-in `<?php the_title(); ?>` method, `<?php the_subtitles(); ?>` tag accepts three parameters:

**$before**  
*(string)* Text to place before  each subtitle. Defaults to "".

**$after**  
*(string)* Text to place after  each subtitle. Defaults to "".

**$echo**  
*(boolean)* If true, display the subtitle in HTML. If false, return the subtitle for use in PHP. Defaults to true.

Things are slightly different in `<?php get_the_subtitles(); ?>`:

**$post**  
*(int|object)* Post or custom post type object or ID.

**$before**  
*(string)* Text to place before  each subtitle. Defaults to "".


**$after**
*(string)* Text to place after each subtitle. Defaults to "".

**$echo**  
*(boolean)* If true, display the subtitle in HTML. If false, return the subtitles for use in PHP. Defaults to true.

For full details on the template tags and their arguments, [view the documentation here in Future](https://github.com/talhamalik/wp-multiple-subtitles//wiki).

By default, subtitle are supported by both posts and pages. To add support for custom post types use add_post_type_support( 'my_post_type', 'gi_wp_subtitles' ).

##### Note :
If *$before* and *$after*  are kept empty then plugin will return SEO Friendly 'List Items' with 'STRONG' tag"".

Installation
------------

1. Upload the WP Subtitle plugin to your WordPress site in the `/wp-content/plugins` folder or install via the WordPress admin by uploading.
1. Activate it from the Wordpress plugin admin screen.
1. Edit your page and/or post template and use the `<?php the_subtitles(); ?>` template tag where you'd like the subtitle to appear.

For full details on the template tags and their arguments, [view the documentation here](https://github.com/talhamalik/wp-multiple-subtitles/wiki).

Frequently Asked Questions
--------------------------

__What does WP Multiple Subtitles do?__  

The plugin adds Subtitle fields when editing posts or pages. The subtitles are stored as a custom field (post meta data) and can be output using template tags.

__Where does WP Multiple Subtitles store the subtitles?__  

All subtitles are stored as post meta data. Deactivating this plugin will not remove those fields.

__How do I add the subtitles to my pages?__  

Refer to [the documentation](https://github.com/talhamalik/wp-multiple-subtitles/wiki) in future.

__How do I add support for custom post types?__  

To add support for custom post types use add_post_type_support( 'my_post_type', 'gi_wp_subtitles' ):

`
function my_wp_multiple_subtitles_page_part_support() {
	add_post_type_support( 'my_post_type', 'gi_wp_subtitles' );
}
add_action( 'init', 'my_wp_multiple_subtitles_page_part_support' );
`

__Where can I get help?__  

All reported issues on github repository are welcomed.

__How should I report a bug?__  

Please submit bugs/errors directly to the [GitHub Issues](https://github.com/talhamalik/wp-multiple-subtitles/issues) list.

__How can I contribute code?__  

The plugin is [hosted on GitHub](https://github.com/talhamalik/wp-multiple-subtitles) and all pull requests are welcome. Please make separate branch for every new pull request.

Upgrade Notice
--------------

#### 1.0
Will be available soon with full error fixes and post subtitles support

#### 0.9 Beta
Initial release.

Changelog
---------

View a list of all plugin changes in [CHANGELOG.md](https://github.com/talhamalik/wp-multiple-subtitles/blob/master/CHANGELOG.md).
