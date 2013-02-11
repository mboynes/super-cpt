=== SuperCPT ===
Contributors: mboynes, unionstreetmedia
Tags: custom-post-types, custom-post-type, cms, custom-field, custom-fields, meta, custom-taxonomy, custom-taxonomies
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 0.1.3
License: GPLv2 or later

Insanely easy and attractive custom post types, custom post meta, and custom taxonomies

== Description ==

SuperCPT is an object wrapper for Custom Post Types, Custom Taxonomies, and Custom Post Meta "for coders, by coders." Simply put, SuperCPT:

* <acronym title="Don't Repeat Yourself">DRY</acronym>s up the Custom Post Type and Custom Taxonomy process (e.g. automatically adds the name to all the labels),
* allows you to set default options for all your Custom Post Types and Taxonomies,
* significantly simplifies the process of creating, saving, and displaying Custom Post Meta,
* is sexy! Your custom fields are styled to look great and SuperCPT comes with 350 awesome icons courtesy of [glyphicons.com](http://glyphicons.com).

= Demo Video =

[vimeo http://vimeo.com/59368054]

= TextMate/Sublime Text 2 Bundle =

If you use TextMate, Sublime Text 2, or another editor which supports TextMate bundles, check out [this set of snippets](https://github.com/mboynes/super-cpt-bundle) to turbo-charge your development.

= And more... =

See the [Other Notes tab](http://wordpress.org/extend/plugins/super-cpt/other_notes/) for instructions and demo code. Find more demos and a full reference [at GitHub](https://github.com/mboynes/super-cpt/wiki).

Since you're a hard-core coder, [check this out on GitHub](https://github.com/mboynes/super-cpt) if you want to contribute!


== Installation ==

Upload the SuperCPT plugin to your blog/site and activate it. You know, like every other plugin.


== Instructions ==

Depending on when and where you're declaring your Custom Post Types and Taxonomies, you have different options for which action to hook onto. `after_setup_theme` is the safest bet, but if you're referencing this in another plugin, `plugins_loaded` is a good choice. To avoid a fatal error if something goes awry, you should check to see if the class `Super_Custom_Post_Type` exists before referencing it. Don't worry about keeping up, reference code is below.


= Custom Post Types =

To define a new Custom Post Type, instantiate the `Super_Custom_Post_Type` class with a string for the post type. For example,

	$movies = new Super_Custom_Post_Type( 'movie' );

It works very much like [`register_post_type`](http://codex.wordpress.org/Function_Reference/register_post_type). The first thing you gained by using this is that the labels all got setup with either 'Movie' or 'Movies'. If our post type were 'indie-film', the labels would have "Indie Film" and "Indie Films" as appropriate. Of course, you do have the ability to set the plural word in cases such as goose/geese. You also gained the ability to define your own custom post type defaults through a filter. Lastly, you gained access to `Super_Custom_Post_Type`'s parent class, `Super_Custom_Post_Meta`, for fast, clean, intuitive custom post meta, which we'll go into shortly.

Lastly, if you've built a lot of custom post types, you're probably sick and tired of the pushpin icon. SuperCPT comes with 350 gorgeous icons courtesy of [glyphicons.com](http://glyphicons.com) that are extremely easy to implement. Here's what it looks like:

	$movies->set_icon( 'film' );


= Custom Taxonomies =

To define a new Custom Taxonomy, much like with Custom Post Types, you instantiate `Super_Custom_Taxonomy` with a string for the term name. For example:

	$actors = new Super_Custom_Taxonomy( 'actor' );

Again, we got free labels for doing this, using either 'Actor' or 'Actors' as appropriate, without needing to specify the 16 labels individually.


= Custom Post Meta =

Custom Post Meta is where SuperCPT shines the brightest, because this process is typically the most time-consuming. `Super_Custom_Post_Meta` is a free-standing class that can be added to any post type, even built-in post types (posts and pages). This class has a method `add_meta_box` which does the bulk of the work, and somewhat mimics the WordPress function. Here's an example:

	$movies->add_meta_box( array(
		'id' => 'features',
		'fields' => array(
			'tagline' => array( 'type' => 'text' )
		)
	) );

The method `add_meta_box` takes an array of parameters (unlike the core function which takes normal ordered arguments). `id` is the only required attribute, and that becomes the ID of the meta box as well as the title (this will get converted into "words" for the title, e.g. `"movie_details"` would become "Movie Details"). `fields` is an array of all the fields in the meta box. It's an associative array, where the keys in the array are the field names and the values are another associative array of attributes for the field. The keys closely reflect the HTML attributes in the resulting field, and any key not known by the plugin will in fact become an HTML attribute (e.g. passing `'data-src' => 'foo'` would become the HTML attribute `data-src="foo"` in the field). See the reference for the full gamut of options, both for the `add_meta_box` argument array and the fields array.

Long story short, using this class means you don't have to do any additional work to store data, retrieve data, style the boxes, and so on.


= Helper Functions =

SuperCPT has a couple of helper functions for displaying your post meta. `get_scpt_formatted_meta` and `the_scpt_formatted_meta`


== Demo Code ==

Here is the full demo code:

	function scpt_demo() {
		if ( ! class_exists( 'Super_Custom_Post_Type' ) )
			return;

		$demo_posts = new Super_Custom_Post_Type( 'demo-post' );

		# Test Icon. Should be a square grid.
		$demo_posts->set_icon( 'show_thumbnails' );

		# Taxonomy test, should be like tags
		$tax_tags = new Super_Custom_Taxonomy( 'tax-tag' );

		# Taxonomy test, should be like categories
		$tax_cats = new Super_Custom_Taxonomy( 'tax-cat', 'Tax Cat', 'Tax Cats', 'category' );

		# Connect both of the above taxonomies with the post type
		connect_types_and_taxes( $demo_posts, array( $tax_tags, $tax_cats ) );

		# Add a meta box with every field type
		$demo_posts->add_meta_box( array(
			'id' => 'demo-fields',
			'context' => 'normal',
			'fields' => array(
				'textbox-demo' => array(),
				'textarea-demo' => array( 'type' => 'textarea' ),
				'wysiwyg-demo' => array( 'type' => 'wysiwyg' ),
				'boolean-demo' => array( 'type' => 'boolean' ),
				'checkboxes-demo' => array( 'type' => 'checkbox', 'options' => array( 'one', 'two', 'three' ) ),
				'radio-buttons-demo' => array( 'type' => 'radio', 'options' => array( 'one', 'two', 'three' ) ),
				'select-demo' => array( 'type' => 'select', 'options' => array( 1 => 'one', 2 => 'two', 3 => 'three' ) ),
				'multi-select-demo' => array( 'type' => 'select', 'options' => array( 'one', 'two', 'three' ), 'multiple' => 'multiple' ),
				'date-demo' => array( 'type' => 'date' ),
				'label-override-demo' => array( 'label' => 'Label Demo' )
			)
		) );

		# Add another CPT to test one-to-one (it could just as easily be one-to-many or many-to-many)
		$linked_posts = new Super_Custom_Post_Type( 'linked-post', 'Other Post', 'Other Posts' );
		$linked_posts->add_meta_box( array(
			'id' => 'one-to-one',
			'title' => 'Testing One-to-One relationship',
			'context' => 'side',
			'fields' => array(
				'demo-posts' => array( 'type' => 'select', 'data' => 'demo-post' ),
				'side-wysiwyg' => array( 'type' => 'wysiwyg' )
			)
		) );
		$linked_posts->set_icon( 'cogwheels' );

	}
	add_action( 'after_setup_theme', 'scpt_demo' );


== Changelog ==

= 0.1 =
Beta Release. Everything is new!


== Frequently Asked Questions ==

= Have any of these questions actually ever been asked? =

Negative.

= I'm not a programmer, can I/how do I use this plugin? =

You probably shouldn't. Check out [Custom Post Type UI](http://wordpress.org/extend/plugins/custom-post-type-ui/), [More Fields](http://wordpress.org/extend/plugins/more-fields/), and [Types - Custom Fields and Custom Post Types Management](http://wordpress.org/extend/plugins/types/).




== Upgrade Notice ==

= 0.1.3 =

Adding scpt_ namespacing to global variables `$known_meta` and `$known_custom_fields` to avoid (albeit by long-odds) conflict with another plugin. If you're using these variables, please update your code to use `$scpt_known_meta` and `$scpt_known_custom_fields`.

= 0.1 =

Beta release


== To-Do ==

1. Add better support for multiple fields for one meta key
2. Add ability to easily include custom icons
3. Add easy RSS feeds, e.g. in fields array, a parameter might be `'rss' => 'PubDate'` to prefer that field's data over the post's publication date.
4. I18n updates


== Donate ==

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="22PRU6U4U78RC">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=22PRU6U4U78RC