<?php


if (!function_exists('get_scpt_formatted_meta')) {
	$known_meta = array();

	/**
	 * Get a formatted post_meta value for a given key.
	 * These are "formatted" in that the returned value varies based on the field type
	 * (assuming it was registered with the SuperCPT Plugin)
	 *
	 * @param string $key The meta key
	 * @return mixed Depending on field type, will return either a string, a boolean value, or an array
	 * @author Matthew Boynes
	 */
	function get_scpt_formatted_meta($key) {
		global $known_custom_fields, $known_meta;
		if ( isset($known_meta[$key]) )
			return $known_meta[$key];

		$value = get_post_meta( get_the_ID(), $key );
		if (!$value || !is_array($value)) return set_known_scpt_meta($key, $value);
		if (!is_array($known_custom_fields) || !isset($known_custom_fields[$key]) || !$known_custom_fields[$key])
			return set_known_scpt_meta($key, $value[0]);

		if (is_array($known_custom_fields[$key])) {
			if ($known_custom_fields[$key]['data']) {
				// print_r($value);die;
				return set_known_scpt_meta($key, get_posts(array('post_type' => $known_custom_fields[$key]['data'], 'include' => $value)));
			}
		}
		else switch ($known_custom_fields[$key]) {
			case 'boolean':
			case 'checkbox':
				if (count($value) == 1 && $value[0] == '1')
					return set_known_scpt_meta($key, true);
				elseif (count($value) == 1 && $value[0] == '0')
					return set_known_scpt_meta($key, false);
			case 'checkbox':
			case 'radio':
			case 'multiple_select':
				return set_known_scpt_meta($key, $value);
			case 'wysiwyg':
				return set_known_scpt_meta($key, wpautop($value[0]));
			case 'date':
			case 'datetime':
				return set_known_scpt_meta($key, strtotime($value[0]));
		}
		return set_known_scpt_meta($key, $value[0]);
	}

	/**
	 * Pseudo meta caching function. Stores value in $known_meta so it doesn't have to get formatted repeatedly
	 *
	 * @param string $key 
	 * @param mixed $value 
	 * @return $value
	 * @author Matthew Boynes
	 */
	function set_known_scpt_meta($key, $value) {
		global $known_meta;
		$known_meta[$key] = $value;
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
	function the_scpt_formatted_meta($key, $sep=', ') {
		$val = get_scpt_formatted_meta($key);
		if (is_array($val))
			echo implode($sep, $val);
		else
			echo $val;
	}
}


if (!function_exists('connect_types_to_taxes')) {
	/**
	 * Connect post types to custom taxonomies
	 *
	 * @uses SuperCustomPostType::connect_taxes
	 * @uses SuperCustomTaxonomy::connect_post_types
	 * @param array|object $types Either a SuperCustomPostType object, or an array of them
	 * @param array|object $taxes Either a SuperCustomTaxonomy object, or an array of them
	 * @return void
	 * @author Matthew Boynes
	 */
	function connect_types_and_taxes($types, $taxes) {
		if (!is_array($types)) $types = array($types);
		if (!is_array($taxes)) $taxes = array($taxes);
		foreach ($types as $type) {
			foreach ($taxes as $tax) {
				$type->connect_taxes($tax->name);
				$tax->connect_post_types($type->type);
			}
		}
	}
}


?>