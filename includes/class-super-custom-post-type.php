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
		if ( isset( $customizations['menu_icon'] ) && ( is_array( $customizations['menu_icon'] ) || false === strpos( $customizations['menu_icon'], '.' ) ) ) {
			$this->set_icon( $customizations['menu_icon'] );
			unset( $customizations['menu_icon'] ); # here we unset it because it will get set properly in the default array
		}

		$this->cpt = array_merge(
			apply_filters( 'scpt_plugin_default_cpt_options', array(
				'label' => $this->plural,
				'labels' => array(
					'name'               => _x( $this->plural, $this->type ),
					'singular_name'      => _x( $this->singular, $this->type ),
					'add_new'            => _x( 'New ' . $this->singular, $this->type ),
					'add_new_item'       => _x( 'Add New ' . $this->singular, $this->type ),
					'edit_item'          => _x( 'Edit ' . $this->singular, $this->type ),
					'new_item'           => _x( 'New ' . $this->singular, $this->type ),
					'view_item'          => _x( 'View ' . $this->singular, $this->type ),
					'search_items'       => _x( 'Search ' . $this->plural, $this->type ),
					'not_found'          => _x( 'No ' . $this->plural . ' found', $this->type ),
					'not_found_in_trash' => _x( 'No ' . $this->plural . ' found in Trash', $this->type ),
					'parent_item_colon'  => _x( 'Parent ' . $this->singular . ':', $this->type ),
				),
				'description'         => $this->plural,
				'public'              => true,
				'menu_position'       => 5, # => Below posts
				'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'page-attributes' ),
				'has_archive'         => true,
				'menu_icon'           => $this->icon ? $this->icon : false,
				'taxonomies'          => array(),
				# These are other values mentioned for reference, but WP's defaults are sufficient
				# 'exclude_from_search' => opposite of 'public'
				# 'publicly_queryable'  => {value of public},
				# 'show_ui'             => {value of public},
				# 'show_in_nav_menus'   => {value of public},
				# 'show_in_menu'        => {value of show_ui},
				# 'show_in_admin_bar'   => {value of show_in_menu}
				# 'capability_type'     => 'post',
				# 'hierarchical'        => false,
				# 'rewrite'             => true,
				# 'query_var'           => true,
				# 'can_export'          => true,
			), $this->type ),
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
		foreach ( $taxes as &$tax ) {
			if ( $tax instanceof Super_Custom_Taxonomy )
				$tax = $tax->name;
		}
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
		add_action( 'init', array( $this, 'register_cpt' ) );
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
	 * Set an icon given an index and name, e.g. array( 'library' => 'font_awesome', 'name' => 'calendar' )
	 *
	 * @param string|array $name If string, it's assumed to be a part of the library 'font_awesome'
	 * @return void
	 * @author Matthew Boynes
	 */
	public function set_icon( $name ) {
		if ( !is_array( $name ) )
			$name = array( 'library' => 'font_awesome', 'name' => $name );

		if ( isset( $name['library'] ) ) {
			if ( 'font_awesome' == $name['library'] )
				require_once SCPT_PLUGIN_DIR . '/includes/class-scpt-font-awesome.php';
			$this->icon = apply_filters( 'scpt_plugin_icon_' . $name['library'], 'none', $name, $this->type );
		}
	}


	/**
	 * Magic Method! Call this to get or set individual arguments for the custom post type. This is a shortcut for calling $object->cpt['argument'].
	 * For instance:
	 * 		$slide->hierarchical()       === $slide->cpt['hierarchical']
	 * 		$slide->hierarchical( true ) === $slide->cpt['hierarchical'] = true;
	 *
	 * Furthermore, if you pass multiple arguments to the method, those will be interpreted as an array. For instance:
	 * 		$slide->supports( 'title', 'editor' ) === $slide->cpt['supports'] = array( 'title', 'editor' )
	 *
	 * @param string $name The function call
	 * @param array $arguments The arguments passed to the function
	 * @return mixed
	 */
	public function __call( $name, $arguments ) {
		$c = count( $arguments );
		if ( 0 == $c ) {
			if ( isset( $this->cpt[ $name ] ) )
				return $this->cpt[ $name ];
			switch ( $name ) {
				case 'exclude_from_search' : return ! $this->cpt['public'];
				case 'publicly_queryable'  : return $this->cpt['public'];
				case 'show_ui'             : return $this->cpt['public'];
				case 'show_in_nav_menus'   : return $this->cpt['public'];
				case 'show_in_menu'        : return $this->show_ui();
				case 'show_in_admin_bar'   : return $this->show_in_menu();
				case 'capability_type'     : return 'post';
				case 'hierarchical'        : return false;
				case 'taxonomies'          : return array();
				case 'rewrite'             : return true;
				case 'query_var'           : return true;
				case 'can_export'          : return true;
			}
			return null;
		} elseif ( 1 == $c ) {
			$this->cpt[ $name ] = $arguments[0];
		} else {
			$this->cpt[ $name ] = $arguments;
		}
	}

}


?>