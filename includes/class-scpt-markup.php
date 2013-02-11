<?php


/**
*
*/
class SCPT_Markup {
	function __construct() {

	}

	/**
	 * Generate an HTML tag
	 *
	 * @uses attributes
	 * @static
	 * @param string $name The tag name, e.g. div, input, p, li, etc.
	 * @param array $attr Optional. The HTML attributes as an associative array
	 * @param mixed $text Optional. The HTML to go within the tag. If false, it's a self-closing tag (< />)
	 * @return string
	 * @author Matthew Boynes
	 */
	public static function tag( $name, $attr = array(), $text = false ) {
		if ( $attr )
			$attr = SCPT_Markup::attributes( $attr );
		else
			$attr = '';

		if ( false !== $text )
			$text = ">$text</$name>";
		else
			$text = ' />';

		return '<' . $name . $attr . $text;
	}

	/**
	 * Takes an associative array and converts the key=>val pairs into HTML attributes
	 *
	 * @static
	 * @param array $arr An associative array of HTML attributes, e.g. array( 'href'=>'http://google.com', 'class'=>'button' )
	 * @return string
	 * @author Matthew Boynes
	 */
	public static function attributes( $arr ) {
		$ret = '';
		foreach ( $arr as $key => $val ) $ret .= ' ' . $key . '="' . $val . '"';
		return $ret;
	}

	/**
	 * Convert a field name into words, appropriate for labels or headers.
	 * Examples: "event-date" becomes "Event Date", "post_tags[]" becomes "Post Tags", "page[name]" becomes "Page Name"
	 *
	 * @static
	 * @param string $field The field to convert
	 * @return string
	 * @author Matthew Boynes
	 */
	public static function labelify( $field ) {
		$search = array( '-', '_', '[]', '[', ']' );
		$replace = array( ' ', ' ', '', ' ', '' );
		return ucwords( str_replace( $search, $replace, $field ) );
	}


}



?>