<?php


if ( !function_exists( 'get_scpt_formatted_meta' ) ) {
	$known_meta = array( );

	/**
	 * Get a formatted post_meta value for a given key.
	 * These are "formatted" in that the returned value varies based on the field type
	 * (assuming it was registered with the SuperCPT Plugin)
	 *
	 * @param string $key The meta key
	 * @return mixed Depending on field type, will return either a string, a boolean value, or an array
	 * @author Matthew Boynes
	 */
	function get_scpt_formatted_meta( $key ) {
		global $known_meta, $known_custom_fields, $post;
		if ( isset( $known_meta[$post->ID][$key] ) )
			return $known_meta[$post->ID][$key];

		$value = get_post_meta( $post->ID, $key );
		if ( !$value || !is_array( $value ) ) return set_known_scpt_meta( $key, $value );

		if ( ! $field_info = get_known_field_info( $key ) )
			return set_known_scpt_meta( $key, $value[0] );

		if ( is_array( $field_info ) ) {
			if ( $field_info['data'] ) {
				return set_known_scpt_meta( $key, get_posts( array( 'post_type' => $field_info['data'], 'include' => $value ) ) );
			}
		}
		else switch ( $field_info ) {
			case 'boolean':
			case 'checkbox':
				if ( 1 == count( $value ) && '1' == $value[0] )
					return set_known_scpt_meta( $key, true );
				elseif ( 1 == count( $value ) )
					return set_known_scpt_meta( $key, false );
				# no break here
			case 'checkbox':
			case 'multiple_select':
				return set_known_scpt_meta( $key, $value );
				break;
			case 'radio':
				return set_known_scpt_meta( $key, $value[0] );
				break;
			case 'wysiwyg':
				return set_known_scpt_meta( $key, wpautop( $value[0] ) );
				break;
			case 'date':
			case 'datetime':
				return set_known_scpt_meta( $key, strtotime( $value[0] ) );
				break;
		}
		return set_known_scpt_meta( $key, $value[0] );
	}

	function get_known_field_info( $key ) {
		global $known_custom_fields, $post;
		if ( !is_array( $known_custom_fields ) || !isset( $known_custom_fields[$post->post_type] ) || !isset( $known_custom_fields[$post->post_type][$key] ) || !$known_custom_fields[$post->post_type][$key] )
			return false;
		return $known_custom_fields[$post->post_type][$key];
	}

	/**
	 * Pseudo meta caching function. Stores value in $known_meta so it doesn't have to get formatted repeatedly
	 *
	 * @param string $key 
	 * @param mixed $value 
	 * @return $value
	 * @author Matthew Boynes
	 */
	function set_known_scpt_meta( $key, $value ) {
		global $known_meta, $post;
		$known_meta[$post->ID][$key] = $value;
		return $value;
	}

	/**
	 * Output results from get_scpt_formatted_meta; if the result is an array, implode it with an optional separator
	 *
	 * @see get_scpt_formatted_meta
	 * @param string $key
	 * @param string $sep Optional. If the resulting meta_value is an array, the array will be imploded with this separator
	 * @return void
	 * @author Matthew Boynes
	 */
	function the_scpt_formatted_meta( $key, $sep=', ' ) {
		$val = apply_filters( 'scpt_plugin_formatted_meta', get_scpt_formatted_meta( $key ), $key );
		if ( is_array( $val ) )
			echo implode( $sep, $val );
		else
			echo $val;
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
		if ( !is_array( $types ) ) $types = array( $types );
		if ( !is_array( $taxes ) ) $taxes = array( $taxes );
		foreach ( $types as $type ) {
			foreach ( $taxes as $tax ) {
				$type->connect_taxes( $tax->name );
				$tax->connect_post_types( $type->type );
			}
		}
	}
}


?>