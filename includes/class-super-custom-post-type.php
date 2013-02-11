<?php

/**
 * Easy-as-pie custom post types
 *
 * @author Matthew Boynes
 */

class Super_Custom_Post_Type extends Super_Custom_Post_Meta {

	/**
	 * The CPT slug, e.g. event
	 *
	 * @var string
	 */
	var $type;


	/**
	 * The singular name for our CPT, e.g. Goose
	 *
	 * @var string
	 */
	var $singular;


	/**
	 * The plural name for our CPT, e.g. Geese
	 *
	 * @var string
	 */
	var $plural;


	/**
	 * Holds the information passed to register_post_type
	 *
	 * @var array See {@link http://codex.wordpress.org/Function_Reference/register_post_type the WordPress Codex}
	 */
	var $cpt;


	/**
	 * The path to the icon ( intially becomes a template for the path)
	 *
	 * @var string
	 */
	var $icon = false;


	/**
	 * Store the root name of the icon chosen, for spriting and other purposes
	 *
	 * @var string
	 */
	var $icon_name;


	/**
	 * Initialize a Custom Post Type
	 *
	 * @uses SCPT_Markup::labelify
	 * @param string $type The Custom Post Type slug. Should be singular, all lowercase, letters and numbers only, dashes for spaces
	 * @param string $singular Optional. The singular form of our CPT to be used in menus, etc. If absent, $type gets converted to words
	 * @param string $plural Optional. The plural form of our CTP to be used in menus, etc. If absent, 's' is added to $singular
	 * @param array|bool $register Optional. If false, the CPT won't be automatically registered. If an array, can override any of the CPT defaults. See {@link http://codex.wordpress.org/Function_Reference/register_post_type the WordPress Codex} for possible values.
	 * @author Matthew Boynes
	 */
	function __construct( $type, $singular = false, $plural = false, $register = array() ) {
		$this->type = $type;
		if ( !$singular )
			$singular = SCPT_Markup::labelify( $this->type );
		if ( !$plural )
			$plural = $singular . 's';
		$this->singular = $singular;
		$this->plural = $plural;

		if ( false !== $register )
			$this->register_post_type( $register );

		parent::__construct( $type );
	}


	/**
	 * Prepare our CPT. See the code itself for our defaults, any of which can be overridden by passing that key in an array to this method
	 *
	 * @uses register_cpt_action
	 * @param array $customizations Overrides to the CPT defaults
	 * @return void
	 * @author Matthew Boynes
	 */
	public function register_post_type( $customizations = array() ) {
		if ( isset( $customizations['menu_icon'] ) && false === strpos( $customizations['menu_icon'], '.' ) ) {
			$this->set_icon( $customizations['menu_icon'] );
			unset( $customizations['menu_icon'] ); # here we unset it because it will get set properly in the default array
		}

		$this->cpt = array_merge(
			apply_filters( 'scpt_plugin_default_cpt_options', array(
				'label' => $this->plural,
				'labels' => array(
					'name' => _x( $this->plural, $this->type ),
					'singular_name' => _x( $this->singular, $this->type ),
					'add_new' => _x( 'New ' . $this->singular, $this->type ),
					'add_new_item' => _x( 'Add New ' . $this->singular, $this->type ),
					'edit_item' => _x( 'Edit ' . $this->singular, $this->type ),
					'new_item' => _x( 'New ' . $this->singular, $this->type ),
					'view_item' => _x( 'View ' . $this->singular, $this->type ),
					'search_items' => _x( 'Search ' . $this->plural, $this->type ),
					'not_found' => _x( 'No ' . $this->plural . ' found', $this->type ),
					'not_found_in_trash' => _x( 'No ' . $this->plural . ' found in Trash', $this->type ),
					'parent_item_colon' => _x( 'Parent ' . $this->singular . ':', $this->type ),
					'menu_name' => _x( $this->plural, $this->type ),
					),
				'description' => $this->plural,
				'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 5, # => Below posts
				'capability_type' => 'post',
				'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'page-attributes' ),
				'has_archive' => true,
				'show_in_nav_menus' => true,
				'taxonomies' => array(),
				'menu_icon' => $this->icon ? sprintf( $this->icon, 16 ) : false,
				# These are other values mentioned for reference, but WP's defaults are sufficient
				# 'hierarchical' => false,
				# 'rewrite' => true,
				# 'query_var' => true,
				# 'can_export' => true,
			) ),
			$customizations
		);

		$this->register_cpt_action();
	}


	/**
	 * Connect this post type to one or more taxonomies
	 *
	 * @param string|array $taxes The taxonomy/ies to connect
	 * @return void
	 * @author Matthew Boynes
	 */
	public function connect_taxes( $taxes ) {
		if ( !is_array( $taxes ) ) $taxes = array( $taxes );
		$this->cpt['taxonomies'] = array_merge( $this->cpt['taxonomies'], $taxes );
	}


	/**
	 * Hook our CPT into WordPress
	 *
	 * @see register_cpt
	 * @return void
	 * @author Matthew Boynes
	 */
	protected function register_cpt_action() {
		add_action( 'init', array( &$this, 'register_cpt' ) );
	}


	/**
	 * Register our CPT
	 *
	 * @see register_cpt_action
	 * @return void
	 * @author Matthew Boynes
	 */
	public function register_cpt() {
		register_post_type( $this->type, $this->cpt );
	}


	/**
	 * Set an icon given an index and name, e.g. 078_warning_sign
	 *
	 * @param string $name
	 * @return string
	 * @author Matthew Boynes
	 */
	public function set_icon( $name ) {
		$this->icon_name = $name;
		$this->icon = SCPT_PLUGIN_URL . 'images/%d/' . $name . '.png';
		if ( $this->cpt ) {
			$this->cpt['menu_icon'] = sprintf( $this->icon, 16 );
		}
		add_filter( 'sanitize_html_class', array( &$this, 'post_icon' ), 10, 2 );
		return $this->icon;
	}


	public function post_icon( $sanitized, $class ) {
		if ( 'icon32-posts-' . $this->type == $class ) {
			$sanitized .= ' glyphicons_' . $this->icon_name;
		}
		return $sanitized;
	}


}


?>