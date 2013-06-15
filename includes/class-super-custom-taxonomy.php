<?php

/**
 * Easy-as-pie custom post types
 *
 * @author Matthew Boynes
 */

class Super_Custom_Taxonomy {

	/**
	 * The tax slug, e.g. event
	 *
	 * @var string
	 */
	public $name;


	/**
	 * The singular name for our tax, e.g. Goose
	 *
	 * @var string
	 */
	public $singular;


	/**
	 * The plural name for our tax, e.g. Geese
	 *
	 * @var string
	 */
	public $plural;


	/**
	 * Does this tax have hierarchy like categories?
	 *
	 * @var bool
	 */
	public $hierarchical = false;


	/**
	 * Objects to which this tax applies
	 *
	 * @var array
	 */
	public $objects = array();


	/**
	 * Holds the information passed to register_taxonomy
	 *
	 * @var array See {@link http://codex.wordpress.org/Function_Reference/register_taxonomy the WordPress Codex}
	 */
	public $tax = array();


	/**
	 * Initialize a Custom Post Type
	 *
	 * @uses SCPT_Markup::labelify
	 * @param string $name The Custom Post Type slug. Should be singular, all lowercase, letters and numbers only, dashes for spaces
	 * @param string $singular Optional. The singular form of our tax to be used in menus, etc. If absent, $name gets converted to words
	 * @param string $plural Optional. The plural form of our tax to be used in menus, etc. If absent, 's' is added to $singular
	 * @param string|bool $acts_like Optional. Define if this should act like categories or tags. If this starts with 'cat' the tax will act like a category; any other value and it will act like a tag
	 * @param array|bool $register Optional. If false, the tax won't be automatically registered. If an array, can override any of the tax defaults. See {@link http://codex.wordpress.org/Function_Reference/register_taxonomy the WordPress Codex} for possible values.
	 * @author Matthew Boynes
	 */
	public function __construct( $name, $singular = false, $plural = false, $acts_like = false, $register = array() ) {
		$this->name = $name;
		if ( !$singular )
			$singular = SCPT_Markup::labelify( $this->name );
		if ( !$plural )
			$plural = $singular . 's';
		$this->singular = $singular;
		$this->plural = $plural;
		$this->hierarchical = ( $acts_like && false !== strpos( strtolower( $acts_like ), 'cat' ) );

		if ( false !== $register )
			$this->register_taxonomy( $register );
	}


	/**
	 * Prepare our tax. See the code itself for our defaults, any of which can be overridden by passing that key in an array to this method
	 *
	 * @uses register_tax_action
	 * @param array $customizations Overrides to the tax defaults
	 * @return void
	 * @author Matthew Boynes
	 */
	public function register_taxonomy( $customizations = array(), $hierarchical = false ) {
		$this->args = array_merge(
			apply_filters( 'scpt_plugin_default_tax_options', array(
				'label' => $this->plural,
				'labels' => array(
					'name'                       => _x( $this->plural, $this->name ),
					'singular_name'              => _x( $this->singular, $this->name ),
					'search_items'               => _x( 'Search ' . $this->plural, $this->name ),
					'popular_items'              => _x( 'Popular ' . $this->plural, $this->name ),
					'all_items'                  => _x( 'All ' . $this->plural, $this->name ),
					'parent_item'                => _x( 'Parent ' . $this->singular, $this->name ),
					'parent_item_colon'          => _x( 'Parent ' . $this->singular . ':', $this->name ),
					'edit_item'                  => _x( 'Edit ' . $this->singular, $this->name ),
					'update_item'                => _x( 'Update ' . $this->singular, $this->name ),
					'add_new_item'               => _x( 'Add New ' . $this->singular, $this->name ),
					'new_item_name'              => _x( 'New ' . $this->singular . ' Name', $this->name ),
					'separate_items_with_commas' => _x( 'Separate ' . strtolower( $this->plural ) . ' with commas', $this->name ),
					'add_or_remove_items'        => _x( 'Add or remove ' . strtolower( $this->plural ), $this->name ),
					'choose_from_most_used'      => _x( 'Choose from the most used ' . strtolower( $this->plural ), $this->name ),
					'menu_name'                  => _x( $this->plural, $this->name ),
				),
				'hierarchical' => $this->hierarchical,
				# These defaults don't need to be overridden:
				# 'public'                => true,
				# 'show_ui'               => {value of public},
				# 'show_in_nav_menus'     => {value of public},
				# 'show_tagcloud'         => {value of show_ui},
				# 'show_admin_column'     => false,
				# 'update_count_callback' => false,
				# 'query_var'             => $this->name,
				# 'rewrite'               => true,
				# 'capabilities'          => array(),
				# 'sort'                  => false,
			), $this->name ),
			$customizations
		);

		$this->register_tax_action();
	}


	/**
	 * Connect one or more post types to this tax
	 *
	 * @param string|array $post_type The post type(s) to connect
	 * @return void
	 * @author Matthew Boynes
	 */
	public function connect_post_types( $post_types ) {
		if ( !is_array( $post_types ) ) $post_types = array( $post_types );
		foreach ( $post_types as &$post_type ) {
			if ( $post_type instanceof Super_Custom_Post_Type )
				$post_type = $post_type->type;
		}
		$this->objects = array_merge( $this->objects, $post_types );
	}


	/**
	 * Hook our tax into WordPress
	 *
	 * @see register_tax
	 * @return void
	 * @author Matthew Boynes
	 */
	protected function register_tax_action() {
		add_action( 'init', array( $this, 'register_tax' ) );
	}


	/**
	 * Register our tax
	 *
	 * @see register_tax_action
	 * @return void
	 * @author Matthew Boynes
	 */
	public function register_tax() {
		register_taxonomy( $this->name, $this->objects, $this->args );
	}


	/**
	 * Override taxonomy options after construction
	 *
	 * @param string|array $overrides Either an associative array of options for register_taxonomy or
	 *  a string, which is to be one of the array's keys. If string, $value must also be set
	 * @param mixed $value The value pair to the key if $overrides is a string
	 * @return void
	 * @author Matthew Boynes
	 */
	public function args( $overrides, $value = null ) {
		if ( is_string( $overrides ) && null !== $value )
			$overrides = array( $overrides => $value );
		elseif ( ! is_array( $overrides ) )
			return;
		$this->args = array_merge( $this->args, $overrides );
	}


	/**
	 * Magic Method! Call this to get or set individual arguments for the custom taxonomy. This is a shortcut for calling $object->args['argument'].
	 * For instance:
	 * 		$slide->hierarchical()       === $slide->args['hierarchical']
	 * 		$slide->hierarchical( true ) === $slide->args['hierarchical'] = true;
	 *
	 * @param string $name The function call
	 * @param array $arguments The arguments passed to the function (there should only be one)
	 * @return mixed
	 */
	public function __call( $name, $arguments ) {
		if ( 0 == count( $arguments ) ) {
			if ( isset( $this->args[ $name ] ) )
				return $this->args[ $name ];

			switch ( $name ) {
				case 'public'                : return true;
				case 'show_ui'               : return $this->public();
				case 'show_in_nav_menus'     : return $this->public();
				case 'show_tagcloud'         : return $this->show_ui();
				case 'show_admin_column'     : return false;
				case 'update_count_callback' : return false;
				case 'query_var'             : return $this->name;
				case 'rewrite'               : return true;
				case 'capabilities'          : return array();
				case 'sort'                  : return false;
			}
			return null;
		} else {
			$this->args[ $name ] = $arguments[0];
		}
	}

}


?>