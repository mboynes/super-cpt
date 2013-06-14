<?php

/**
 * A custom integration of the Font Awesome icon library for SuperCPT
 */

if ( !class_exists( 'SCPT_Font_Awesome' ) ) :

class SCPT_Font_Awesome {

	private static $instance;

	public $styles = array();

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function __clone() { wp_die( "Please don't __clone SCPT_Font_Awesome" ); }

	public function __wakeup() { wp_die( "Please don't __wakeup SCPT_Font_Awesome" ); }

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new SCPT_Font_Awesome;
			self::$instance->setup();
		}
		return self::$instance;
	}

	public function setup() {
		add_filter( 'scpt_plugin_icon_font_awesome', array( $this, 'set_font_awesome_icon' ), 10, 3 );
		add_action( 'admin_print_styles', array( $this, 'add_styles' ) );
		add_action( 'scpt_plugin_icon_demos', array( $this, 'icon_demo' ) );
	}


	/**
	 * Add styles to the site <head> if applicable
	 *
	 * @return void
	 */
	public function add_styles() {
		if ( !empty( $this->styles ) ) :
			?>
			<style type="text/css">
				<?php do_action( 'scpt_plugin_icon_css' ) ?>
			</style>
			<?php
		endif;
	}


	/**
	 * Set an icon for a post type from the Font Awesome library
	 *
	 * @param string $none 'none', shouldn't be changed here.
	 * @param array $icon the array argument passed to Super_Custom_Post_Type::set_icon()
	 * @param string $post_type
	 * @return string
	 */
	public function set_font_awesome_icon( $none, $icon, $post_type ) {
		if ( isset( $icon['name'] ) ) {
			$this->register_font_awesome( $post_type );
			$content = $this->get_font_awesome_icon( $icon['name'] );
			$this->styles['font_awesome']['rules'][] = "#adminmenu #menu-posts-{$post_type} div.wp-menu-image:before { content: '{$content}' !important; }";
		}
		return $none;
	}


	/**
	 * We're going to be using Font Awesome for icons, so prepare the CSS that will be injected into the page
	 *
	 * @param string $post_type
	 * @return void
	 */
	public function register_font_awesome( $post_type ) {
		$this->styles['font_awesome']['types'][] = $post_type;
		$dir = SCPT_PLUGIN_URL;
		if ( !isset( $this->styles['font_awesome']['base'] ) ) {
			$this->styles['font_awesome']['base'] = "
			@font-face { font-family: 'FontAwesome'; src: url('{$dir}fonts/fontawesome-webfont.eot?v=3.1.0'); src: url('{$dir}fonts/fontawesome-webfont.eot?#iefix&v=3.1.0') format('embedded-opentype'), url('{$dir}fonts/fontawesome-webfont.woff?v=3.1.0') format('woff'), url('{$dir}fonts/fontawesome-webfont.ttf?v=3.1.0') format('truetype'), url('{$dir}fonts/fontawesome-webfont.svg#fontawesomeregular?v=3.1.0') format('svg'); font-weight: normal; font-style: normal; }
			%s { font-family: FontAwesome !important; -webkit-font-smoothing: antialiased; background: none; *margin-right: .3em; }
			%s { font-family: FontAwesome !important; }
			";
			add_action( 'scpt_plugin_icon_css', array( $this, 'output_font_awesome' ) );
		}
	}


	/**
	 * Output relevant styles for Font Awesome
	 * @return type
	 */
	public function output_font_awesome() {
		$normal = $before = array();
		foreach ( $this->styles['font_awesome']['types'] as $post_type ) {
			$temp = "#adminmenu #menu-posts-{$post_type} div.wp-menu-image";
			$normal[] = $temp;
			$before[] = $temp . ':before';
		}
		printf( $this->styles['font_awesome']['base'], implode( ',', $normal ), implode( ',', $before ) );
		foreach ( $this->styles['font_awesome']['rules'] as $rule ) {
			echo $rule;
		}
	}


	/**
	 * Return the appropriate character for a given Font Awesome icon
	 *
	 * @param string $icon
	 * @return string
	 */
	public function get_font_awesome_icon( $icon ) {
		switch ( $icon ) {

			case 'glass' :                return '\f000';
			case 'music' :                return '\f001';
			case 'search' :               return '\f002';
			case 'envelope' :             return '\f003';
			case 'heart' :                return '\f004';
			case 'star' :                 return '\f005';
			case 'star-empty' :           return '\f006';
			case 'user' :                 return '\f007';
			case 'film' :                 return '\f008';
			case 'th-large' :             return '\f009';
			case 'th' :                   return '\f00a';
			case 'th-list' :              return '\f00b';
			case 'ok' :                   return '\f00c';
			case 'remove' :               return '\f00d';
			case 'zoom-in' :              return '\f00e';

			case 'zoom-out' :             return '\f010';
			case 'off' :                  return '\f011';
			case 'signal' :               return '\f012';
			case 'cog' :                  return '\f013';
			case 'trash' :                return '\f014';
			case 'home' :                 return '\f015';
			case 'file' :                 return '\f016';
			case 'time' :                 return '\f017';
			case 'road' :                 return '\f018';
			case 'download-alt' :         return '\f019';
			case 'download' :             return '\f01a';
			case 'upload' :               return '\f01b';
			case 'inbox' :                return '\f01c';
			case 'play-circle' :          return '\f01d';
			case 'repeat' :               return '\f01e';
			case 'rotate-right' :         return '\f01e';

			/* F020 doesn't work in Safari. all shifted one down */
			case 'refresh' :              return '\f021';
			case 'list-alt' :             return '\f022';
			case 'lock' :                 return '\f023';
			case 'flag' :                 return '\f024';
			case 'headphones' :           return '\f025';
			case 'volume-off' :           return '\f026';
			case 'volume-down' :          return '\f027';
			case 'volume-up' :            return '\f028';
			case 'qrcode' :               return '\f029';
			case 'barcode' :              return '\f02a';
			case 'tag' :                  return '\f02b';
			case 'tags' :                 return '\f02c';
			case 'book' :                 return '\f02d';
			case 'bookmark' :             return '\f02e';
			case 'print' :                return '\f02f';

			case 'camera' :               return '\f030';
			case 'font' :                 return '\f031';
			case 'bold' :                 return '\f032';
			case 'italic' :               return '\f033';
			case 'text-height' :          return '\f034';
			case 'text-width' :           return '\f035';
			case 'align-left' :           return '\f036';
			case 'align-center' :         return '\f037';
			case 'align-right' :          return '\f038';
			case 'align-justify' :        return '\f039';
			case 'list' :                 return '\f03a';
			case 'indent-left' :          return '\f03b';
			case 'indent-right' :         return '\f03c';
			case 'facetime-video' :       return '\f03d';
			case 'picture' :              return '\f03e';

			case 'pencil' :               return '\f040';
			case 'map-marker' :           return '\f041';
			case 'adjust' :               return '\f042';
			case 'tint' :                 return '\f043';
			case 'edit' :                 return '\f044';
			case 'share' :                return '\f045';
			case 'check' :                return '\f046';
			case 'move' :                 return '\f047';
			case 'step-backward' :        return '\f048';
			case 'fast-backward' :        return '\f049';
			case 'backward' :             return '\f04a';
			case 'play' :                 return '\f04b';
			case 'pause' :                return '\f04c';
			case 'stop' :                 return '\f04d';
			case 'forward' :              return '\f04e';

			case 'fast-forward' :         return '\f050';
			case 'step-forward' :         return '\f051';
			case 'eject' :                return '\f052';
			case 'chevron-left' :         return '\f053';
			case 'chevron-right' :        return '\f054';
			case 'plus-sign' :            return '\f055';
			case 'minus-sign' :           return '\f056';
			case 'remove-sign' :          return '\f057';
			case 'ok-sign' :              return '\f058';
			case 'question-sign' :        return '\f059';
			case 'info-sign' :            return '\f05a';
			case 'screenshot' :           return '\f05b';
			case 'remove-circle' :        return '\f05c';
			case 'ok-circle' :            return '\f05d';
			case 'ban-circle' :           return '\f05e';

			case 'arrow-left' :           return '\f060';
			case 'arrow-right' :          return '\f061';
			case 'arrow-up' :             return '\f062';
			case 'arrow-down' :           return '\f063';
			case 'share-alt' :            return '\f064';
			case 'mail-forward' :         return '\f064';
			case 'resize-full' :          return '\f065';
			case 'resize-small' :         return '\f066';
			case 'plus' :                 return '\f067';
			case 'minus' :                return '\f068';
			case 'asterisk' :             return '\f069';
			case 'exclamation-sign' :     return '\f06a';
			case 'gift' :                 return '\f06b';
			case 'leaf' :                 return '\f06c';
			case 'fire' :                 return '\f06d';
			case 'eye-open' :             return '\f06e';

			case 'eye-close' :            return '\f070';
			case 'warning-sign' :         return '\f071';
			case 'plane' :                return '\f072';
			case 'calendar' :             return '\f073';
			case 'random' :               return '\f074';
			case 'comment' :              return '\f075';
			case 'magnet' :               return '\f076';
			case 'chevron-up' :           return '\f077';
			case 'chevron-down' :         return '\f078';
			case 'retweet' :              return '\f079';
			case 'shopping-cart' :        return '\f07a';
			case 'folder-close' :         return '\f07b';
			case 'folder-open' :          return '\f07c';
			case 'resize-vertical' :      return '\f07d';
			case 'resize-horizontal' :    return '\f07e';

			case 'bar-chart' :            return '\f080';
			case 'twitter-sign' :         return '\f081';
			case 'facebook-sign' :        return '\f082';
			case 'camera-retro' :         return '\f083';
			case 'key' :                  return '\f084';
			case 'cogs' :                 return '\f085';
			case 'comments' :             return '\f086';
			case 'thumbs-up' :            return '\f087';
			case 'thumbs-down' :          return '\f088';
			case 'star-half' :            return '\f089';
			case 'heart-empty' :          return '\f08a';
			case 'signout' :              return '\f08b';
			case 'linkedin-sign' :        return '\f08c';
			case 'pushpin' :              return '\f08d';
			case 'external-link' :        return '\f08e';

			case 'signin' :               return '\f090';
			case 'trophy' :               return '\f091';
			case 'github-sign' :          return '\f092';
			case 'upload-alt' :           return '\f093';
			case 'lemon' :                return '\f094';
			case 'phone' :                return '\f095';
			case 'check-empty' :          return '\f096';
			case 'bookmark-empty' :       return '\f097';
			case 'phone-sign' :           return '\f098';
			case 'twitter' :              return '\f099';
			case 'facebook' :             return '\f09a';
			case 'github' :               return '\f09b';
			case 'unlock' :               return '\f09c';
			case 'credit-card' :          return '\f09d';
			case 'rss' :                  return '\f09e';

			case 'hdd' :                  return '\f0a0';
			case 'bullhorn' :             return '\f0a1';
			case 'bell' :                 return '\f0a2';
			case 'certificate' :          return '\f0a3';
			case 'hand-right' :           return '\f0a4';
			case 'hand-left' :            return '\f0a5';
			case 'hand-up' :              return '\f0a6';
			case 'hand-down' :            return '\f0a7';
			case 'circle-arrow-left' :    return '\f0a8';
			case 'circle-arrow-right' :   return '\f0a9';
			case 'circle-arrow-up' :      return '\f0aa';
			case 'circle-arrow-down' :    return '\f0ab';
			case 'globe' :                return '\f0ac';
			case 'wrench' :               return '\f0ad';
			case 'tasks' :                return '\f0ae';

			case 'filter' :               return '\f0b0';
			case 'briefcase' :            return '\f0b1';
			case 'fullscreen' :           return '\f0b2';

			case 'group' :                return '\f0c0';
			case 'link' :                 return '\f0c1';
			case 'cloud' :                return '\f0c2';
			case 'beaker' :               return '\f0c3';
			case 'cut' :                  return '\f0c4';
			case 'copy' :                 return '\f0c5';
			case 'paper-clip' :           return '\f0c6';
			case 'save' :                 return '\f0c7';
			case 'sign-blank' :           return '\f0c8';
			case 'reorder' :              return '\f0c9';
			case 'list-ul' :              return '\f0ca';
			case 'list-ol' :              return '\f0cb';
			case 'strikethrough' :        return '\f0cc';
			case 'underline' :            return '\f0cd';
			case 'table' :                return '\f0ce';

			case 'magic' :                return '\f0d0';
			case 'truck' :                return '\f0d1';
			case 'pinterest' :            return '\f0d2';
			case 'pinterest-sign' :       return '\f0d3';
			case 'google-plus-sign' :     return '\f0d4';
			case 'google-plus' :          return '\f0d5';
			case 'money' :                return '\f0d6';
			case 'caret-down' :           return '\f0d7';
			case 'caret-up' :             return '\f0d8';
			case 'caret-left' :           return '\f0d9';
			case 'caret-right' :          return '\f0da';
			case 'columns' :              return '\f0db';
			case 'sort' :                 return '\f0dc';
			case 'sort-down' :            return '\f0dd';
			case 'sort-up' :              return '\f0de';

			case 'envelope-alt' :         return '\f0e0';
			case 'linkedin' :             return '\f0e1';
			case 'undo' :                 return '\f0e2';
			case 'rotate-left' :          return '\f0e2';
			case 'legal' :                return '\f0e3';
			case 'dashboard' :            return '\f0e4';
			case 'comment-alt' :          return '\f0e5';
			case 'comments-alt' :         return '\f0e6';
			case 'bolt' :                 return '\f0e7';
			case 'sitemap' :              return '\f0e8';
			case 'umbrella' :             return '\f0e9';
			case 'paste' :                return '\f0ea';
			case 'lightbulb' :            return '\f0eb';
			case 'exchange' :             return '\f0ec';
			case 'cloud-download' :       return '\f0ed';
			case 'cloud-upload' :         return '\f0ee';

			case 'user-md' :              return '\f0f0';
			case 'stethoscope' :          return '\f0f1';
			case 'suitcase' :             return '\f0f2';
			case 'bell-alt' :             return '\f0f3';
			case 'coffee' :               return '\f0f4';
			case 'food' :                 return '\f0f5';
			case 'file-alt' :             return '\f0f6';
			case 'building' :             return '\f0f7';
			case 'hospital' :             return '\f0f8';
			case 'ambulance' :            return '\f0f9';
			case 'medkit' :               return '\f0fa';
			case 'fighter-jet' :          return '\f0fb';
			case 'beer' :                 return '\f0fc';
			case 'h-sign' :               return '\f0fd';
			case 'plus-sign-alt' :        return '\f0fe';

			case 'double-angle-left' :    return '\f100';
			case 'double-angle-right' :   return '\f101';
			case 'double-angle-up' :      return '\f102';
			case 'double-angle-down' :    return '\f103';
			case 'angle-left' :           return '\f104';
			case 'angle-right' :          return '\f105';
			case 'angle-up' :             return '\f106';
			case 'angle-down' :           return '\f107';
			case 'desktop' :              return '\f108';
			case 'laptop' :               return '\f109';
			case 'tablet' :               return '\f10a';
			case 'mobile-phone' :         return '\f10b';
			case 'circle-blank' :         return '\f10c';
			case 'quote-left' :           return '\f10d';
			case 'quote-right' :          return '\f10e';

			case 'spinner' :              return '\f110';
			case 'circle' :               return '\f111';
			case 'reply' :                return '\f112';
			case 'mail-reply' :           return '\f112';
			case 'folder-close-alt' :     return '\f114';
			case 'folder-open-alt' :      return '\f115';
			case 'expand-alt' :           return '\f116';
			case 'collapse-alt' :         return '\f117';
			case 'smile' :                return '\f118';
			case 'frown' :                return '\f119';
			case 'meh' :                  return '\f11a';
			case 'gamepad' :              return '\f11b';
			case 'keyboard' :             return '\f11c';
			case 'flag-alt' :             return '\f11d';
			case 'flag-checkered' :       return '\f11e';

			case 'terminal' :             return '\f120';
			case 'code' :                 return '\f121';
			case 'reply-all' :            return '\f122';
			case 'mail-reply-all' :       return '\f122';
			case 'star-half-full' :       return '\f123';
			case 'star-half-empty' :      return '\f123';
			case 'location-arrow' :       return '\f124';
			case 'crop' :                 return '\f125';
			case 'code-fork' :            return '\f126';
			case 'unlink' :               return '\f127';
			case 'question' :             return '\f128';
			case 'info' :                 return '\f129';
			case 'exclamation' :          return '\f12a';
			case 'superscript' :          return '\f12b';
			case 'subscript' :            return '\f12c';
			case 'eraser' :               return '\f12d';
			case 'puzzle-piece' :         return '\f12e';

			case 'microphone' :           return '\f130';
			case 'microphone-off' :       return '\f131';
			case 'shield' :               return '\f132';
			case 'calendar-empty' :       return '\f133';
			case 'fire-extinguisher' :    return '\f134';
			case 'rocket' :               return '\f135';
			case 'maxcdn' :               return '\f136';
			case 'chevron-sign-left' :    return '\f137';
			case 'chevron-sign-right' :   return '\f138';
			case 'chevron-sign-up' :      return '\f139';
			case 'chevron-sign-down' :    return '\f13a';
			case 'html5' :                return '\f13b';
			case 'css3' :                 return '\f13c';
			case 'anchor' :               return '\f13d';
			case 'unlock-alt' :           return '\f13e';

			case 'bullseye' :             return '\f140';
			case 'ellipsis-horizontal' :  return '\f141';
			case 'ellipsis-vertical' :    return '\f142';
			case 'rss-sign' :             return '\f143';
			case 'play-sign' :            return '\f144';
			case 'ticket' :               return '\f145';
			case 'minus-sign-alt' :       return '\f146';
			case 'check-minus' :          return '\f147';
			case 'level-up' :             return '\f148';
			case 'level-down' :           return '\f149';
			case 'check-sign' :           return '\f14a';
			case 'edit-sign' :            return '\f14b';
			case 'external-link-sign' :   return '\f14c';
			case 'share-sign' :           return '\f14d';
		}
		return false;
	}


	/**
	 * Output icons in the demo grid
	 *
	 * @return void
	 */
	public function icon_demo() {
		$icons = array(
			'glass',
			'music',
			'search',
			'envelope',
			'heart',
			'star',
			'star-empty',
			'user',
			'film',
			'th-large',
			'th',
			'th-list',
			'ok',
			'remove',
			'zoom-in',

			'zoom-out',
			'off',
			'signal',
			'cog',
			'trash',
			'home',
			'file',
			'time',
			'road',
			'download-alt',
			'download',
			'upload',
			'inbox',
			'play-circle',
			'repeat',
			'rotate-right',

			/* F020 doesn't work in Safari. all shifted one down */
			'refresh',
			'list-alt',
			'lock',
			'flag',
			'headphones',
			'volume-off',
			'volume-down',
			'volume-up',
			'qrcode',
			'barcode',
			'tag',
			'tags',
			'book',
			'bookmark',
			'print',

			'camera',
			'font',
			'bold',
			'italic',
			'text-height',
			'text-width',
			'align-left',
			'align-center',
			'align-right',
			'align-justify',
			'list',
			'indent-left',
			'indent-right',
			'facetime-video',
			'picture',

			'pencil',
			'map-marker',
			'adjust',
			'tint',
			'edit',
			'share',
			'check',
			'move',
			'step-backward',
			'fast-backward',
			'backward',
			'play',
			'pause',
			'stop',
			'forward',

			'fast-forward',
			'step-forward',
			'eject',
			'chevron-left',
			'chevron-right',
			'plus-sign',
			'minus-sign',
			'remove-sign',
			'ok-sign',
			'question-sign',
			'info-sign',
			'screenshot',
			'remove-circle',
			'ok-circle',
			'ban-circle',

			'arrow-left',
			'arrow-right',
			'arrow-up',
			'arrow-down',
			'share-alt',
			'mail-forward',
			'resize-full',
			'resize-small',
			'plus',
			'minus',
			'asterisk',
			'exclamation-sign',
			'gift',
			'leaf',
			'fire',
			'eye-open',

			'eye-close',
			'warning-sign',
			'plane',
			'calendar',
			'random',
			'comment',
			'magnet',
			'chevron-up',
			'chevron-down',
			'retweet',
			'shopping-cart',
			'folder-close',
			'folder-open',
			'resize-vertical',
			'resize-horizontal',

			'bar-chart',
			'twitter-sign',
			'facebook-sign',
			'camera-retro',
			'key',
			'cogs',
			'comments',
			'thumbs-up',
			'thumbs-down',
			'star-half',
			'heart-empty',
			'signout',
			'linkedin-sign',
			'pushpin',
			'external-link',

			'signin',
			'trophy',
			'github-sign',
			'upload-alt',
			'lemon',
			'phone',
			'check-empty',
			'bookmark-empty',
			'phone-sign',
			'twitter',
			'facebook',
			'github',
			'unlock',
			'credit-card',
			'rss',

			'hdd',
			'bullhorn',
			'bell',
			'certificate',
			'hand-right',
			'hand-left',
			'hand-up',
			'hand-down',
			'circle-arrow-left',
			'circle-arrow-right',
			'circle-arrow-up',
			'circle-arrow-down',
			'globe',
			'wrench',
			'tasks',

			'filter',
			'briefcase',
			'fullscreen',

			'group',
			'link',
			'cloud',
			'beaker',
			'cut',
			'copy',
			'paper-clip',
			'save',
			'sign-blank',
			'reorder',
			'list-ul',
			'list-ol',
			'strikethrough',
			'underline',
			'table',

			'magic',
			'truck',
			'pinterest',
			'pinterest-sign',
			'google-plus-sign',
			'google-plus',
			'money',
			'caret-down',
			'caret-up',
			'caret-left',
			'caret-right',
			'columns',
			'sort',
			'sort-down',
			'sort-up',

			'envelope-alt',
			'linkedin',
			'undo',
			'rotate-left',
			'legal',
			'dashboard',
			'comment-alt',
			'comments-alt',
			'bolt',
			'sitemap',
			'umbrella',
			'paste',
			'lightbulb',
			'exchange',
			'cloud-download',
			'cloud-upload',

			'user-md',
			'stethoscope',
			'suitcase',
			'bell-alt',
			'coffee',
			'food',
			'file-alt',
			'building',
			'hospital',
			'ambulance',
			'medkit',
			'fighter-jet',
			'beer',
			'h-sign',
			'plus-sign-alt',

			'double-angle-left',
			'double-angle-right',
			'double-angle-up',
			'double-angle-down',
			'angle-left',
			'angle-right',
			'angle-up',
			'angle-down',
			'desktop',
			'laptop',
			'tablet',
			'mobile-phone',
			'circle-blank',
			'quote-left',
			'quote-right',

			'spinner',
			'circle',
			'reply',
			'mail-reply',
			'folder-close-alt',
			'folder-open-alt',
			'expand-alt',
			'collapse-alt',
			'smile',
			'frown',
			'meh',
			'gamepad',
			'keyboard',
			'flag-alt',
			'flag-checkered',

			'terminal',
			'code',
			'reply-all',
			'mail-reply-all',
			'star-half-full',
			'star-half-empty',
			'location-arrow',
			'crop',
			'code-fork',
			'unlink',
			'question',
			'info',
			'exclamation',
			'superscript',
			'subscript',
			'eraser',
			'puzzle-piece',

			'microphone',
			'microphone-off',
			'shield',
			'calendar-empty',
			'fire-extinguisher',
			'rocket',
			'maxcdn',
			'chevron-sign-left',
			'chevron-sign-right',
			'chevron-sign-up',
			'chevron-sign-down',
			'html5',
			'css3',
			'anchor',
			'unlock-alt',

			'bullseye',
			'ellipsis-horizontal',
			'ellipsis-vertical',
			'rss-sign',
			'play-sign',
			'ticket',
			'minus-sign-alt',
			'check-minus',
			'level-up',
			'level-down',
			'check-sign',
			'edit-sign',
			'external-link-sign',
			'share-sign',
		);
		?>
		<style type="text/css">
			@font-face { font-family: 'FontAwesome'; src: url('<?php echo SCPT_PLUGIN_URL ?>fonts/fontawesome-webfont.eot?v=3.1.0'); src: url('<?php echo SCPT_PLUGIN_URL ?>fonts/fontawesome-webfont.eot?#iefix&v=3.1.0') format('embedded-opentype'), url('<?php echo SCPT_PLUGIN_URL ?>fonts/fontawesome-webfont.woff?v=3.1.0') format('woff'), url('<?php echo SCPT_PLUGIN_URL ?>fonts/fontawesome-webfont.ttf?v=3.1.0') format('truetype'), url('<?php echo SCPT_PLUGIN_URL ?>fonts/fontawesome-webfont.svg#fontawesomeregular?v=3.1.0') format('svg'); font-weight: normal; font-style: normal; }
			#font_awesome_icons dt:before { font-family: FontAwesome !important; -webkit-font-smoothing: antialiased; *margin-right: .3em; }
			<?php foreach ( $icons as $icon ) : ?>
			.font-awesome-icon-<?php echo $icon ?>:before { content: '<?php echo $this->get_font_awesome_icon( $icon ) ?>'; }
			<?php endforeach ?>
		</style>
		<div id="font_awesome_icons">
			<?php foreach ( $icons as $icon ) : ?>
				<dl><dt class="font-awesome-icon-<?php echo $icon ?>"></dt><dd><?php echo $icon ?></dd></dl>
			<?php endforeach ?>
		</div>
		<?php
	}

}

function SCPT_Font_Awesome() {
	return SCPT_Font_Awesome::instance();
}
SCPT_Font_Awesome();

endif;