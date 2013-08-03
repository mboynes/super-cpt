<?php


if ( !function_exists( 'get_scpt_formatted_meta' ) ) {
	$scpt_known_meta = array();

	/**
	 * Get a formatted post_meta value for a given key.
	 * These are "formatted" in that the returned value varies based on the field type
	 * (assuming it was registered with the SuperCPT Plugin)
	 *
	 * @param string $key The meta key
	 * @return mixed Depending on field type, will return either a string, a boolean value, or an array
	 * @author Matthew Boynes
	 */
	function get_scpt_formatted_meta( $key, $post_id = false ) {
		global $scpt_known_meta, $scpt_known_custom_fields;

		if ( false == $post_id )
			$post_id = get_the_ID();

		if ( isset( $scpt_known_meta[ $post_id ][ $key ] ) )
			return $scpt_known_meta[ $post_id ][ $key ];

		$value = get_post_meta( $post_id, $key );
		if ( !$value || !is_array( $value ) ) return set_known_scpt_meta( $key, $value, $post_id );

		if ( ! $field_info = get_known_field_info( $key, $post_id ) )
			return set_known_scpt_meta( $key, $value[0], $post_id );

		if ( is_array( $field_info ) ) {
			if ( $field_info['data'] ) {
				return set_known_scpt_meta( $key, get_posts( array( 'post_type' => $field_info['data'], 'include' => $value ) ), $post_id );
			}
		}
		else switch ( $field_info ) {
			case 'boolean':
			case 'checkbox':
				if ( 1 == count( $value ) && '1' == $value[0] )
					return set_known_scpt_meta( $key, true, $post_id );
				elseif ( 1 == count( $value ) )
					return set_known_scpt_meta( $key, false, $post_id );
				# no break here
			case 'checkbox':
			case 'multiple_select':
				return set_known_scpt_meta( $key, $value, $post_id );
				break;
			case 'radio':
				return set_known_scpt_meta( $key, $value[0], $post_id );
				break;
			case 'wysiwyg':
				return set_known_scpt_meta( $key, wpautop( $value[0] ), $post_id );
				break;
			case 'date':
			case 'datetime':
				return set_known_scpt_meta( $key, strtotime( $value[0] ), $post_id );
				break;
		}
		return set_known_scpt_meta( $key, $value[0], $post_id );
	}

	function get_known_field_info( $key, $post_id = false ) {
		global $scpt_known_custom_fields;
		if ( false === $post_id ) {
			$post_type = get_post_type();
		} elseif ( intval( $post_id ) ) {
			$post_type = get_post_type( $post_id );
		} elseif ( is_string( $post_id ) ) {
			$post_type = $post_id;
		}
		if ( !is_array( $scpt_known_custom_fields ) || !isset( $scpt_known_custom_fields[ $post_type ] ) || !isset( $scpt_known_custom_fields[ $post_type ][ $key ] ) || !$scpt_known_custom_fields[ $post_type ][ $key ] )
			return false;
		return $scpt_known_custom_fields[ $post_type ][ $key ];
	}

	/**
	 * Pseudo meta caching function. Stores value in $scpt_known_meta so it doesn't have to get formatted repeatedly
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return $value
	 * @author Matthew Boynes
	 */
	function set_known_scpt_meta( $key, $value, $post_id ) {
		global $scpt_known_meta;
		$scpt_known_meta[ $post_id ][ $key ] = $value;
		return $value;
	}

	/**
	 * Output results from get_scpt_formatted_meta; if the result is an array, implode it with an optional
	 * separator. Must be used inside the loop.
	 *
	 * @see get_scpt_formatted_meta
	 * @param string $key
	 * @param string $sep Optional. If the resulting meta_value is an array, the array will be imploded with this separator
	 * @return void
	 * @author Matthew Boynes
	 */
	function the_scpt_formatted_meta( $key, $sep = ', ' ) {
		$val = apply_filters( 'scpt_plugin_formatted_meta', get_scpt_formatted_meta( $key ), $key );
		if ( is_array( $val ) )
			echo implode( $sep, $val );
		else
			echo $val;
	}


	/**
	 * Get a list of meta fields for a given post type or the current post
	 *
	 * @param string $post_type Optional. If absent, uses the post type of the current post
	 * @return array
	 */
	function get_scpt_meta_fields( $post_type = false ) {
		global $scpt_known_custom_fields;
		if ( false == $post_type ) {
			$post_type = $GLOBALS['post']->post_type;
		}
		return isset( $scpt_known_custom_fields[ $post_type ] ) ? $scpt_known_custom_fields[ $post_type ] : array();
	}


	/**
	 * Get an array of meta fields for a given post or the current one.
	 * Thanks to Aaron Holbrook (aaronholbrook)
	 *
	 * @param int $post_id Optional. If absent, uses the post ID of the current post
	 * @param array|string $args Optional. Options are:
	 * 		'single_only' => the $single param for get_post_meta. All or nothing!
	 * @return array
	 */
	function get_scpt_meta_data( $post_id = false, $args = array() ) {
		$args = wp_parse_args( $args, array(
			'single_only' => true
		) );
		$meta = array();
		if ( false == $post_id ) {
			$post_id = get_the_ID();
		}
		if ( $post_id ) {
			$meta_fields = get_scpt_meta_fields( get_post_type( $post_id ) );
			foreach ( $meta_fields as $key => $type ) {
				$meta[ $key ] = get_post_meta( $post_id, $key, $args['single_only'] );
			}
		}
		return $meta;
	}
}


if ( !function_exists( 'connect_types_and_taxes' ) ) {
	/**
	 * Connect post types to custom taxonomies
	 *
	 * @uses Super_Custom_Post_Type::connect_taxes
	 * @uses Super_Custom_Taxonomy::connect_post_types
	 * @param array|object $types Either a Super_Custom_Post_Type object, or an array of them
	 * @param array|object $taxes Either a Super_Custom_Taxonomy object, or an array of them
	 * @return void
	 * @author Matthew Boynes
	 */
	function connect_types_and_taxes( $types, $taxes ) {
		if ( ! is_array( $types ) )
			$types = array( $types );

		if ( ! is_array( $taxes ) )
			$taxes = array( $taxes );

		foreach ( (array) $types as $type )
			$type->connect_taxes( $taxes );

		foreach ( (array) $taxes as $tax )
			$tax->connect_post_types( $types );
	}
}

