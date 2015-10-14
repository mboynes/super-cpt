<?php

/**
 * A custom integration of the Font Awesome icon library for SuperCPT
 */

if ( !class_exists( 'SCPT_Font_Awesome' ) ) :

class SCPT_Font_Awesome {

	private static $instance;

	public $styles = array();

	public $font_dir = '';

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
		$this->font_dir = SCPT_PLUGIN_URL . 'fonts/';
		$this->styles['icons'] = array();

		add_action( 'admin_print_styles', array( $this, 'add_styles' ) );
		add_action( 'scpt_plugin_icon_demos', array( $this, 'icon_demo' ) );
		add_filter( 'scpt_plugin_icon_font_awesome', array( $this, 'set_font_awesome_icon' ), 10, 3 );
	}


	/**
	 * Add styles to the site <head> if applicable
	 *
	 * @return void
	 */
	public function add_styles() {
		if ( ! empty( $this->styles ) ) :
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
			$this->register_font_awesome();
			$this->styles['icons'][ $post_type ] = $icon['name'];
		}
		return $none;
	}


	/**
	 * We're going to be using Font Awesome for icons, so prepare the CSS that will be injected into the page
	 *
	 * @param string $post_type
	 * @return void
	 */
	public function register_font_awesome() {
		if ( ! isset( $this->styles['base'] ) ) {
			$this->styles['base'] = "
			%s { font-family:FontAwesome !important;display:inline-block;font-style:normal;font-weight:normal;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;background: none }
			%s { font-family:FontAwesome !important; font-size: 19px }";
			add_action( 'scpt_plugin_icon_css', array( $this, 'output_font_awesome' ) );
		}
	}


	/**
	 * Output relevant styles for Font Awesome
	 * @return type
	 */
	public function output_font_awesome() {
		$cache_key = 'scpt-fa440-' . md5( serialize( $this->styles ) );
		if ( false === ( $content = get_transient( $cache_key ) ) ) {
			$content = '';
			$normal = $before = array();
			foreach ( $this->styles['icons'] as $post_type => $icon ) {
				$normal[] = "#adminmenu #menu-posts-{$post_type} div.wp-menu-image";
				$before[] = "#adminmenu #menu-posts-{$post_type} div.wp-menu-image:before";
				$hex = $this->get_font_awesome_icon( $icon );
				$content .= "\n#adminmenu #menu-posts-{$post_type} div.wp-menu-image:before { content: '{$hex}' !important; }";
			}
			$content = sprintf( $this->styles['base'], implode( ',', $normal ), implode( ',', $before ) ) . $content;
			set_transient( $cache_key, $content, HOUR_IN_SECONDS );
		}
		echo $content;
	}


	/**
	 * Return the appropriate character for a given Font Awesome icon
	 *
	 * @param string $icon
	 * @return string
	 */
	public function get_font_awesome_icon( $icon ) {
		# Increase support for previous icon set
		$icon = str_replace( '_', '-', $icon );

		switch ( $icon ) {
			case 'glass' : return '\f000';
			case 'music' : return '\f001';
			case 'search' : return '\f002';
			case 'envelope-o' : return '\f003';
			case 'heart' : return '\f004';
			case 'star' : return '\f005';
			case 'star-o' : return '\f006';
			case 'user' : return '\f007';
			case 'film' : return '\f008';
			case 'th-large' : return '\f009';
			case 'th' : return '\f00a';
			case 'th-list' : return '\f00b';
			case 'check' : return '\f00c';
			case 'times' : return '\f00d';
			case 'search-plus' : return '\f00e';
			case 'search-minus' : return '\f010';
			case 'power-off' : return '\f011';
			case 'signal' : return '\f012';
			case 'gear' : return '\f013';
			case 'cog' : return '\f013';
			case 'trash-o' : return '\f014';
			case 'home' : return '\f015';
			case 'file-o' : return '\f016';
			case 'clock-o' : return '\f017';
			case 'road' : return '\f018';
			case 'download' : return '\f019';
			case 'arrow-circle-o-down' : return '\f01a';
			case 'arrow-circle-o-up' : return '\f01b';
			case 'inbox' : return '\f01c';
			case 'play-circle-o' : return '\f01d';
			case 'rotate-right' : return '\f01e';
			case 'repeat' : return '\f01e';
			case 'refresh' : return '\f021';
			case 'list-alt' : return '\f022';
			case 'lock' : return '\f023';
			case 'flag' : return '\f024';
			case 'headphones' : return '\f025';
			case 'volume-off' : return '\f026';
			case 'volume-down' : return '\f027';
			case 'volume-up' : return '\f028';
			case 'qrcode' : return '\f029';
			case 'barcode' : return '\f02a';
			case 'tag' : return '\f02b';
			case 'tags' : return '\f02c';
			case 'book' : return '\f02d';
			case 'bookmark' : return '\f02e';
			case 'print' : return '\f02f';
			case 'camera' : return '\f030';
			case 'font' : return '\f031';
			case 'bold' : return '\f032';
			case 'italic' : return '\f033';
			case 'text-height' : return '\f034';
			case 'text-width' : return '\f035';
			case 'align-left' : return '\f036';
			case 'align-center' : return '\f037';
			case 'align-right' : return '\f038';
			case 'align-justify' : return '\f039';
			case 'list' : return '\f03a';
			case 'dedent' : return '\f03b';
			case 'outdent' : return '\f03b';
			case 'indent' : return '\f03c';
			case 'video-camera' : return '\f03d';
			case 'picture-o' : return '\f03e';
			case 'pencil' : return '\f040';
			case 'map-marker' : return '\f041';
			case 'adjust' : return '\f042';
			case 'tint' : return '\f043';
			case 'edit' : return '\f044';
			case 'pencil-square-o' : return '\f044';
			case 'share-square-o' : return '\f045';
			case 'check-square-o' : return '\f046';
			case 'arrows' : return '\f047';
			case 'step-backward' : return '\f048';
			case 'fast-backward' : return '\f049';
			case 'backward' : return '\f04a';
			case 'play' : return '\f04b';
			case 'pause' : return '\f04c';
			case 'stop' : return '\f04d';
			case 'forward' : return '\f04e';
			case 'fast-forward' : return '\f050';
			case 'step-forward' : return '\f051';
			case 'eject' : return '\f052';
			case 'chevron-left' : return '\f053';
			case 'chevron-right' : return '\f054';
			case 'plus-circle' : return '\f055';
			case 'minus-circle' : return '\f056';
			case 'times-circle' : return '\f057';
			case 'check-circle' : return '\f058';
			case 'question-circle' : return '\f059';
			case 'info-circle' : return '\f05a';
			case 'crosshairs' : return '\f05b';
			case 'times-circle-o' : return '\f05c';
			case 'check-circle-o' : return '\f05d';
			case 'ban' : return '\f05e';
			case 'arrow-left' : return '\f060';
			case 'arrow-right' : return '\f061';
			case 'arrow-up' : return '\f062';
			case 'arrow-down' : return '\f063';
			case 'mail-forward' : return '\f064';
			case 'share' : return '\f064';
			case 'expand' : return '\f065';
			case 'compress' : return '\f066';
			case 'plus' : return '\f067';
			case 'minus' : return '\f068';
			case 'asterisk' : return '\f069';
			case 'exclamation-circle' : return '\f06a';
			case 'gift' : return '\f06b';
			case 'leaf' : return '\f06c';
			case 'fire' : return '\f06d';
			case 'eye' : return '\f06e';
			case 'eye-slash' : return '\f070';
			case 'warning' : return '\f071';
			case 'exclamation-triangle' : return '\f071';
			case 'plane' : return '\f072';
			case 'calendar' : return '\f073';
			case 'random' : return '\f074';
			case 'comment' : return '\f075';
			case 'magnet' : return '\f076';
			case 'chevron-up' : return '\f077';
			case 'chevron-down' : return '\f078';
			case 'retweet' : return '\f079';
			case 'shopping-cart' : return '\f07a';
			case 'folder' : return '\f07b';
			case 'folder-open' : return '\f07c';
			case 'arrows-v' : return '\f07d';
			case 'arrows-h' : return '\f07e';
			case 'bar-chart-o' : return '\f080';
			case 'twitter-square' : return '\f081';
			case 'facebook-square' : return '\f082';
			case 'camera-retro' : return '\f083';
			case 'key' : return '\f084';
			case 'gears' : return '\f085';
			case 'cogs' : return '\f085';
			case 'comments' : return '\f086';
			case 'thumbs-o-up' : return '\f087';
			case 'thumbs-o-down' : return '\f088';
			case 'star-half' : return '\f089';
			case 'heart-o' : return '\f08a';
			case 'sign-out' : return '\f08b';
			case 'linkedin-square' : return '\f08c';
			case 'thumb-tack' : return '\f08d';
			case 'external-link' : return '\f08e';
			case 'sign-in' : return '\f090';
			case 'trophy' : return '\f091';
			case 'github-square' : return '\f092';
			case 'upload' : return '\f093';
			case 'lemon-o' : return '\f094';
			case 'phone' : return '\f095';
			case 'square-o' : return '\f096';
			case 'bookmark-o' : return '\f097';
			case 'phone-square' : return '\f098';
			case 'twitter' : return '\f099';
			case 'facebook' : return '\f09a';
			case 'github' : return '\f09b';
			case 'unlock' : return '\f09c';
			case 'credit-card' : return '\f09d';
			case 'rss' : return '\f09e';
			case 'hdd-o' : return '\f0a0';
			case 'bullhorn' : return '\f0a1';
			case 'bell' : return '\f0f3';
			case 'certificate' : return '\f0a3';
			case 'hand-o-right' : return '\f0a4';
			case 'hand-o-left' : return '\f0a5';
			case 'hand-o-up' : return '\f0a6';
			case 'hand-o-down' : return '\f0a7';
			case 'arrow-circle-left' : return '\f0a8';
			case 'arrow-circle-right' : return '\f0a9';
			case 'arrow-circle-up' : return '\f0aa';
			case 'arrow-circle-down' : return '\f0ab';
			case 'globe' : return '\f0ac';
			case 'wrench' : return '\f0ad';
			case 'tasks' : return '\f0ae';
			case 'filter' : return '\f0b0';
			case 'briefcase' : return '\f0b1';
			case 'arrows-alt' : return '\f0b2';
			case 'group' : return '\f0c0';
			case 'users' : return '\f0c0';
			case 'chain' : return '\f0c1';
			case 'link' : return '\f0c1';
			case 'cloud' : return '\f0c2';
			case 'flask' : return '\f0c3';
			case 'cut' : return '\f0c4';
			case 'scissors' : return '\f0c4';
			case 'copy' : return '\f0c5';
			case 'files-o' : return '\f0c5';
			case 'paperclip' : return '\f0c6';
			case 'save' : return '\f0c7';
			case 'floppy-o' : return '\f0c7';
			case 'square' : return '\f0c8';
			case 'bars' : return '\f0c9';
			case 'list-ul' : return '\f0ca';
			case 'list-ol' : return '\f0cb';
			case 'strikethrough' : return '\f0cc';
			case 'underline' : return '\f0cd';
			case 'table' : return '\f0ce';
			case 'magic' : return '\f0d0';
			case 'truck' : return '\f0d1';
			case 'pinterest' : return '\f0d2';
			case 'pinterest-square' : return '\f0d3';
			case 'google-plus-square' : return '\f0d4';
			case 'google-plus' : return '\f0d5';
			case 'money' : return '\f0d6';
			case 'caret-down' : return '\f0d7';
			case 'caret-up' : return '\f0d8';
			case 'caret-left' : return '\f0d9';
			case 'caret-right' : return '\f0da';
			case 'columns' : return '\f0db';
			case 'unsorted' : return '\f0dc';
			case 'sort' : return '\f0dc';
			case 'sort-down' : return '\f0dd';
			case 'sort-asc' : return '\f0dd';
			case 'sort-up' : return '\f0de';
			case 'sort-desc' : return '\f0de';
			case 'envelope' : return '\f0e0';
			case 'linkedin' : return '\f0e1';
			case 'rotate-left' : return '\f0e2';
			case 'undo' : return '\f0e2';
			case 'legal' : return '\f0e3';
			case 'gavel' : return '\f0e3';
			case 'dashboard' : return '\f0e4';
			case 'tachometer' : return '\f0e4';
			case 'comment-o' : return '\f0e5';
			case 'comments-o' : return '\f0e6';
			case 'flash' : return '\f0e7';
			case 'bolt' : return '\f0e7';
			case 'sitemap' : return '\f0e8';
			case 'umbrella' : return '\f0e9';
			case 'paste' : return '\f0ea';
			case 'clipboard' : return '\f0ea';
			case 'lightbulb-o' : return '\f0eb';
			case 'exchange' : return '\f0ec';
			case 'cloud-download' : return '\f0ed';
			case 'cloud-upload' : return '\f0ee';
			case 'user-md' : return '\f0f0';
			case 'stethoscope' : return '\f0f1';
			case 'suitcase' : return '\f0f2';
			case 'bell-o' : return '\f0a2';
			case 'coffee' : return '\f0f4';
			case 'cutlery' : return '\f0f5';
			case 'file-text-o' : return '\f0f6';
			case 'building-o' : return '\f0f7';
			case 'hospital-o' : return '\f0f8';
			case 'ambulance' : return '\f0f9';
			case 'medkit' : return '\f0fa';
			case 'fighter-jet' : return '\f0fb';
			case 'beer' : return '\f0fc';
			case 'h-square' : return '\f0fd';
			case 'plus-square' : return '\f0fe';
			case 'angle-double-left' : return '\f100';
			case 'angle-double-right' : return '\f101';
			case 'angle-double-up' : return '\f102';
			case 'angle-double-down' : return '\f103';
			case 'angle-left' : return '\f104';
			case 'angle-right' : return '\f105';
			case 'angle-up' : return '\f106';
			case 'angle-down' : return '\f107';
			case 'desktop' : return '\f108';
			case 'laptop' : return '\f109';
			case 'tablet' : return '\f10a';
			case 'mobile-phone' : return '\f10b';
			case 'mobile' : return '\f10b';
			case 'circle-o' : return '\f10c';
			case 'quote-left' : return '\f10d';
			case 'quote-right' : return '\f10e';
			case 'spinner' : return '\f110';
			case 'circle' : return '\f111';
			case 'mail-reply' : return '\f112';
			case 'reply' : return '\f112';
			case 'github-alt' : return '\f113';
			case 'folder-o' : return '\f114';
			case 'folder-open-o' : return '\f115';
			case 'smile-o' : return '\f118';
			case 'frown-o' : return '\f119';
			case 'meh-o' : return '\f11a';
			case 'gamepad' : return '\f11b';
			case 'keyboard-o' : return '\f11c';
			case 'flag-o' : return '\f11d';
			case 'flag-checkered' : return '\f11e';
			case 'terminal' : return '\f120';
			case 'code' : return '\f121';
			case 'reply-all' : return '\f122';
			case 'mail-reply-all' : return '\f122';
			case 'star-half-empty' : return '\f123';
			case 'star-half-full' : return '\f123';
			case 'star-half-o' : return '\f123';
			case 'location-arrow' : return '\f124';
			case 'crop' : return '\f125';
			case 'code-fork' : return '\f126';
			case 'unlink' : return '\f127';
			case 'chain-broken' : return '\f127';
			case 'question' : return '\f128';
			case 'info' : return '\f129';
			case 'exclamation' : return '\f12a';
			case 'superscript' : return '\f12b';
			case 'subscript' : return '\f12c';
			case 'eraser' : return '\f12d';
			case 'puzzle-piece' : return '\f12e';
			case 'microphone' : return '\f130';
			case 'microphone-slash' : return '\f131';
			case 'shield' : return '\f132';
			case 'calendar-o' : return '\f133';
			case 'fire-extinguisher' : return '\f134';
			case 'rocket' : return '\f135';
			case 'maxcdn' : return '\f136';
			case 'chevron-circle-left' : return '\f137';
			case 'chevron-circle-right' : return '\f138';
			case 'chevron-circle-up' : return '\f139';
			case 'chevron-circle-down' : return '\f13a';
			case 'html5' : return '\f13b';
			case 'css3' : return '\f13c';
			case 'anchor' : return '\f13d';
			case 'unlock-alt' : return '\f13e';
			case 'bullseye' : return '\f140';
			case 'ellipsis-h' : return '\f141';
			case 'ellipsis-v' : return '\f142';
			case 'rss-square' : return '\f143';
			case 'play-circle' : return '\f144';
			case 'ticket' : return '\f145';
			case 'minus-square' : return '\f146';
			case 'minus-square-o' : return '\f147';
			case 'level-up' : return '\f148';
			case 'level-down' : return '\f149';
			case 'check-square' : return '\f14a';
			case 'pencil-square' : return '\f14b';
			case 'external-link-square' : return '\f14c';
			case 'share-square' : return '\f14d';
			case 'compass' : return '\f14e';
			case 'toggle-down' : return '\f150';
			case 'caret-square-o-down' : return '\f150';
			case 'toggle-up' : return '\f151';
			case 'caret-square-o-up' : return '\f151';
			case 'toggle-right' : return '\f152';
			case 'caret-square-o-right' : return '\f152';
			case 'euro' : return '\f153';
			case 'eur' : return '\f153';
			case 'gbp' : return '\f154';
			case 'dollar' : return '\f155';
			case 'usd' : return '\f155';
			case 'rupee' : return '\f156';
			case 'inr' : return '\f156';
			case 'cny' : return '\f157';
			case 'rmb' : return '\f157';
			case 'yen' : return '\f157';
			case 'jpy' : return '\f157';
			case 'ruble' : return '\f158';
			case 'rouble' : return '\f158';
			case 'rub' : return '\f158';
			case 'won' : return '\f159';
			case 'krw' : return '\f159';
			case 'bitcoin' : return '\f15a';
			case 'btc' : return '\f15a';
			case 'file' : return '\f15b';
			case 'file-text' : return '\f15c';
			case 'sort-alpha-asc' : return '\f15d';
			case 'sort-alpha-desc' : return '\f15e';
			case 'sort-amount-asc' : return '\f160';
			case 'sort-amount-desc' : return '\f161';
			case 'sort-numeric-asc' : return '\f162';
			case 'sort-numeric-desc' : return '\f163';
			case 'thumbs-up' : return '\f164';
			case 'thumbs-down' : return '\f165';
			case 'youtube-square' : return '\f166';
			case 'youtube' : return '\f167';
			case 'xing' : return '\f168';
			case 'xing-square' : return '\f169';
			case 'youtube-play' : return '\f16a';
			case 'dropbox' : return '\f16b';
			case 'stack-overflow' : return '\f16c';
			case 'instagram' : return '\f16d';
			case 'flickr' : return '\f16e';
			case 'adn' : return '\f170';
			case 'bitbucket' : return '\f171';
			case 'bitbucket-square' : return '\f172';
			case 'tumblr' : return '\f173';
			case 'tumblr-square' : return '\f174';
			case 'long-arrow-down' : return '\f175';
			case 'long-arrow-up' : return '\f176';
			case 'long-arrow-left' : return '\f177';
			case 'long-arrow-right' : return '\f178';
			case 'apple' : return '\f179';
			case 'windows' : return '\f17a';
			case 'android' : return '\f17b';
			case 'linux' : return '\f17c';
			case 'dribbble' : return '\f17d';
			case 'skype' : return '\f17e';
			case 'foursquare' : return '\f180';
			case 'trello' : return '\f181';
			case 'female' : return '\f182';
			case 'male' : return '\f183';
			case 'gittip' : return '\f184';
			case 'sun-o' : return '\f185';
			case 'moon-o' : return '\f186';
			case 'archive' : return '\f187';
			case 'bug' : return '\f188';
			case 'vk' : return '\f189';
			case 'weibo' : return '\f18a';
			case 'renren' : return '\f18b';
			case 'pagelines' : return '\f18c';
			case 'stack-exchange' : return '\f18d';
			case 'arrow-circle-o-right' : return '\f18e';
			case 'arrow-circle-o-left' : return '\f190';
			case 'toggle-left' : return '\f191';
			case 'caret-square-o-left' : return '\f191';
			case 'dot-circle-o' : return '\f192';
			case 'wheelchair' : return '\f193';
			case 'vimeo-square' : return '\f194';
			case 'turkish-lira' : return '\f195';
			case 'try' : return '\f195';
			case 'plus-square-o' : return '\f196';
			case 'space-shuttle' : return '\f197';
			case 'slack' : return '\f198';
			case 'envelope-square' : return '\f199';
			case 'wordpress' : return '\f19a';
			case 'openid' : return '\f19b';
			case 'institution' :
			case 'bank' :
			case 'university' : return '\f19c';
			case 'mortar-board' :
			case 'graduation-cap' : return '\f19d';
			case 'yahoo' : return '\f19e';
			case 'google' : return '\f1a0';
			case 'reddit' : return '\f1a1';
			case 'reddit-square' : return '\f1a2';
			case 'stumbleupon-circle' : return '\f1a3';
			case 'stumbleupon' : return '\f1a4';
			case 'delicious' : return '\f1a5';
			case 'digg' : return '\f1a6';
			case 'pied-piper' : return '\f1a7';
			case 'pied-piper-alt' : return '\f1a8';
			case 'drupal' : return '\f1a9';
			case 'joomla' : return '\f1aa';
			case 'language' : return '\f1ab';
			case 'fax' : return '\f1ac';
			case 'building' : return '\f1ad';
			case 'child' : return '\f1ae';
			case 'paw' : return '\f1b0';
			case 'spoon' : return '\f1b1';
			case 'cube' : return '\f1b2';
			case 'cubes' : return '\f1b3';
			case 'behance' : return '\f1b4';
			case 'behance-square' : return '\f1b5';
			case 'steam' : return '\f1b6';
			case 'steam-square' : return '\f1b7';
			case 'recycle' : return '\f1b8';
			case 'automobile' :
			case 'car' : return '\f1b9';
			case 'cab' :
			case 'taxi' : return '\f1ba';
			case 'tree' : return '\f1bb';
			case 'spotify' : return '\f1bc';
			case 'deviantart' : return '\f1bd';
			case 'soundcloud' : return '\f1be';
			case 'database' : return '\f1c0';
			case 'file-pdf-o' : return '\f1c1';
			case 'file-word-o' : return '\f1c2';
			case 'file-excel-o' : return '\f1c3';
			case 'file-powerpoint-o' : return '\f1c4';
			case 'file-photo-o' :
			case 'file-picture-o' :
			case 'file-image-o' : return '\f1c5';
			case 'file-zip-o' :
			case 'file-archive-o' : return '\f1c6';
			case 'file-sound-o' :
			case 'file-audio-o' : return '\f1c7';
			case 'file-movie-o' :
			case 'file-video-o' : return '\f1c8';
			case 'file-code-o' : return '\f1c9';
			case 'vine' : return '\f1ca';
			case 'codepen' : return '\f1cb';
			case 'jsfiddle' : return '\f1cc';
			case 'life-bouy' :
			case 'life-buoy' :
			case 'life-saver' :
			case 'support' :
			case 'life-ring' : return '\f1cd';
			case 'circle-o-notch' : return '\f1ce';
			case 'ra' :
			case 'rebel' : return '\f1d0';
			case 'ge' :
			case 'empire' : return '\f1d1';
			case 'git-square' : return '\f1d2';
			case 'git' : return '\f1d3';
			case 'y-combinator-square' :
			case 'yc-square' :
			case 'hacker-news' : return '\f1d4';
			case 'tencent-weibo' : return '\f1d5';
			case 'qq' : return '\f1d6';
			case 'wechat' :
			case 'weixin' : return '\f1d7';
			case 'send' :
			case 'paper-plane' : return '\f1d8';
			case 'send-o' :
			case 'paper-plane-o' : return '\f1d9';
			case 'history' : return '\f1da';
			case 'circle-thin' : return '\f1db';
			case 'header' : return '\f1dc';
			case 'paragraph' : return '\f1dd';
			case 'sliders' : return '\f1de';
			case 'share-alt' : return '\f1e0';
			case 'share-alt-square' : return '\f1e1';
			case 'bomb' : return '\f1e2';
			case 'soccer-ball-o' :
			case 'futbol-o' : return '\f1e3';
			case 'tty' : return '\f1e4';
			case 'binoculars' : return '\f1e5';
			case 'plug' : return '\f1e6';
			case 'slideshare' : return '\f1e7';
			case 'twitch' : return '\f1e8';
			case 'yelp' : return '\f1e9';
			case 'newspaper-o' : return '\f1ea';
			case 'wifi' : return '\f1eb';
			case 'calculator' : return '\f1ec';
			case 'paypal' : return '\f1ed';
			case 'google-wallet' : return '\f1ee';
			case 'cc-visa' : return '\f1f0';
			case 'cc-mastercard' : return '\f1f1';
			case 'cc-discover' : return '\f1f2';
			case 'cc-amex' : return '\f1f3';
			case 'cc-paypal' : return '\f1f4';
			case 'cc-stripe' : return '\f1f5';
			case 'bell-slash' : return '\f1f6';
			case 'bell-slash-o' : return '\f1f7';
			case 'trash' : return '\f1f8';
			case 'copyright' : return '\f1f9';
			case 'at' : return '\f1fa';
			case 'eyedropper' : return '\f1fb';
			case 'paint-brush' : return '\f1fc';
			case 'birthday-cake' : return '\f1fd';
			case 'area-chart' : return '\f1fe';
			case 'pie-chart' : return '\f200';
			case 'line-chart' : return '\f201';
			case 'lastfm' : return '\f202';
			case 'lastfm-square' : return '\f203';
			case 'toggle-off' : return '\f204';
			case 'toggle-on' : return '\f205';
			case 'bicycle' : return '\f206';
			case 'bus' : return '\f207';
			case 'ioxhost' : return '\f208';
			case 'angellist' : return '\f209';
			case 'cc' : return '\f20a';
			case 'shekel' :
			case 'sheqel' :
			case 'ils' : return '\f20b';
			case 'meanpath' : return '\f20c';
			case 'buysellads' : return '\f20d';
			case 'connectdevelop' : return '\f20e';
			case 'dashcube' : return '\f210';
			case 'forumbee' : return '\f211';
			case 'leanpub' : return '\f212';
			case 'sellsy' : return '\f213';
			case 'shirtsinbulk' : return '\f214';
			case 'simplybuilt' : return '\f215';
			case 'skyatlas' : return '\f216';
			case 'cart-plus' : return '\f217';
			case 'cart-arrow-down' : return '\f218';
			case 'diamond' : return '\f219';
			case 'ship' : return '\f21a';
			case 'user-secret' : return '\f21b';
			case 'motorcycle' : return '\f21c';
			case 'street-view' : return '\f21d';
			case 'heartbeat' : return '\f21e';
			case 'venus' : return '\f221';
			case 'mars' : return '\f222';
			case 'mercury' : return '\f223';
			case 'intersex' :
			case 'transgender' : return '\f224';
			case 'transgender-alt' : return '\f225';
			case 'venus-double' : return '\f226';
			case 'mars-double' : return '\f227';
			case 'venus-mars' : return '\f228';
			case 'mars-stroke' : return '\f229';
			case 'mars-stroke-v' : return '\f22a';
			case 'mars-stroke-h' : return '\f22b';
			case 'neuter' : return '\f22c';
			case 'genderless' : return '\f22d';
			case 'facebook-official' : return '\f230';
			case 'pinterest-p' : return '\f231';
			case 'whatsapp' : return '\f232';
			case 'server' : return '\f233';
			case 'user-plus' : return '\f234';
			case 'user-times' : return '\f235';
			case 'hotel' :
			case 'bed' : return '\f236';
			case 'viacoin' : return '\f237';
			case 'train' : return '\f238';
			case 'subway' : return '\f239';
			case 'medium' : return '\f23a';
			case 'yc' :
			case 'y-combinator' : return '\f23b';
			case 'optin-monster' : return '\f23c';
			case 'opencart' : return '\f23d';
			case 'expeditedssl' : return '\f23e';
			case 'battery-4' :
			case 'battery-full' : return '\f240';
			case 'battery-3' :
			case 'battery-three-quarters' : return '\f241';
			case 'battery-2' :
			case 'battery-half' : return '\f242';
			case 'battery-1' :
			case 'battery-quarter' : return '\f243';
			case 'battery-0' :
			case 'battery-empty' : return '\f244';
			case 'mouse-pointer' : return '\f245';
			case 'i-cursor' : return '\f246';
			case 'object-group' : return '\f247';
			case 'object-ungroup' : return '\f248';
			case 'sticky-note' : return '\f249';
			case 'sticky-note-o' : return '\f24a';
			case 'cc-jcb' : return '\f24b';
			case 'cc-diners-club' : return '\f24c';
			case 'clone' : return '\f24d';
			case 'balance-scale' : return '\f24e';
			case 'hourglass-o' : return '\f250';
			case 'hourglass-1' :
			case 'hourglass-start' : return '\f251';
			case 'hourglass-2' :
			case 'hourglass-half' : return '\f252';
			case 'hourglass-3' :
			case 'hourglass-end' : return '\f253';
			case 'hourglass' : return '\f254';
			case 'hand-grab-o' :
			case 'hand-rock-o' : return '\f255';
			case 'hand-stop-o' :
			case 'hand-paper-o' : return '\f256';
			case 'hand-scissors-o' : return '\f257';
			case 'hand-lizard-o' : return '\f258';
			case 'hand-spock-o' : return '\f259';
			case 'hand-pointer-o' : return '\f25a';
			case 'hand-peace-o' : return '\f25b';
			case 'trademark' : return '\f25c';
			case 'registered' : return '\f25d';
			case 'creative-commons' : return '\f25e';
			case 'gg' : return '\f260';
			case 'gg-circle' : return '\f261';
			case 'tripadvisor' : return '\f262';
			case 'odnoklassniki' : return '\f263';
			case 'odnoklassniki-square' : return '\f264';
			case 'get-pocket' : return '\f265';
			case 'wikipedia-w' : return '\f266';
			case 'safari' : return '\f267';
			case 'chrome' : return '\f268';
			case 'firefox' : return '\f269';
			case 'opera' : return '\f26a';
			case 'internet-explorer' : return '\f26b';
			case 'tv' :
			case 'television' : return '\f26c';
			case 'contao' : return '\f26d';
			case '500px' : return '\f26e';
			case 'amazon' : return '\f270';
			case 'calendar-plus-o' : return '\f271';
			case 'calendar-minus-o' : return '\f272';
			case 'calendar-times-o' : return '\f273';
			case 'calendar-check-o' : return '\f274';
			case 'industry' : return '\f275';
			case 'map-pin' : return '\f276';
			case 'map-signs' : return '\f277';
			case 'map-o' : return '\f278';
			case 'map' : return '\f279';
			case 'commenting' : return '\f27a';
			case 'commenting-o' : return '\f27b';
			case 'houzz' : return '\f27c';
			case 'vimeo' : return '\f27d';
			case 'black-tie' : return '\f27e';
			case 'fonticons' : return '\f280';

		}
		_deprecated_argument( 'Super_Custom_Post_Type::set_icon', '2.0', "$icon is not a valid icon. See the icon list to find an adequate replacement." );
		return '\f009';
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
			'envelope-o',
			'heart',
			'star',
			'star-o',
			'user',
			'film',
			'th-large',
			'th',
			'th-list',
			'check',
			'times',
			'search-plus',
			'search-minus',
			'power-off',
			'signal',
			'gear',
			'cog',
			'trash-o',
			'home',
			'file-o',
			'clock-o',
			'road',
			'download',
			'arrow-circle-o-down',
			'arrow-circle-o-up',
			'inbox',
			'play-circle-o',
			'rotate-right',
			'repeat',
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
			'dedent',
			'outdent',
			'indent',
			'video-camera',
			'picture-o',
			'pencil',
			'map-marker',
			'adjust',
			'tint',
			'edit',
			'pencil-square-o',
			'share-square-o',
			'check-square-o',
			'arrows',
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
			'plus-circle',
			'minus-circle',
			'times-circle',
			'check-circle',
			'question-circle',
			'info-circle',
			'crosshairs',
			'times-circle-o',
			'check-circle-o',
			'ban',
			'arrow-left',
			'arrow-right',
			'arrow-up',
			'arrow-down',
			'mail-forward',
			'share',
			'expand',
			'compress',
			'plus',
			'minus',
			'asterisk',
			'exclamation-circle',
			'gift',
			'leaf',
			'fire',
			'eye',
			'eye-slash',
			'warning',
			'exclamation-triangle',
			'plane',
			'calendar',
			'random',
			'comment',
			'magnet',
			'chevron-up',
			'chevron-down',
			'retweet',
			'shopping-cart',
			'folder',
			'folder-open',
			'arrows-v',
			'arrows-h',
			'bar-chart-o',
			'twitter-square',
			'facebook-square',
			'camera-retro',
			'key',
			'gears',
			'cogs',
			'comments',
			'thumbs-o-up',
			'thumbs-o-down',
			'star-half',
			'heart-o',
			'sign-out',
			'linkedin-square',
			'thumb-tack',
			'external-link',
			'sign-in',
			'trophy',
			'github-square',
			'upload',
			'lemon-o',
			'phone',
			'square-o',
			'bookmark-o',
			'phone-square',
			'twitter',
			'facebook',
			'github',
			'unlock',
			'credit-card',
			'rss',
			'hdd-o',
			'bullhorn',
			'bell',
			'certificate',
			'hand-o-right',
			'hand-o-left',
			'hand-o-up',
			'hand-o-down',
			'arrow-circle-left',
			'arrow-circle-right',
			'arrow-circle-up',
			'arrow-circle-down',
			'globe',
			'wrench',
			'tasks',
			'filter',
			'briefcase',
			'arrows-alt',
			'group',
			'users',
			'chain',
			'link',
			'cloud',
			'flask',
			'cut',
			'scissors',
			'copy',
			'files-o',
			'paperclip',
			'save',
			'floppy-o',
			'square',
			'bars',
			'list-ul',
			'list-ol',
			'strikethrough',
			'underline',
			'table',
			'magic',
			'truck',
			'pinterest',
			'pinterest-square',
			'google-plus-square',
			'google-plus',
			'money',
			'caret-down',
			'caret-up',
			'caret-left',
			'caret-right',
			'columns',
			'unsorted',
			'sort',
			'sort-down',
			'sort-asc',
			'sort-up',
			'sort-desc',
			'envelope',
			'linkedin',
			'rotate-left',
			'undo',
			'legal',
			'gavel',
			'dashboard',
			'tachometer',
			'comment-o',
			'comments-o',
			'flash',
			'bolt',
			'sitemap',
			'umbrella',
			'paste',
			'clipboard',
			'lightbulb-o',
			'exchange',
			'cloud-download',
			'cloud-upload',
			'user-md',
			'stethoscope',
			'suitcase',
			'bell-o',
			'coffee',
			'cutlery',
			'file-text-o',
			'building-o',
			'hospital-o',
			'ambulance',
			'medkit',
			'fighter-jet',
			'beer',
			'h-square',
			'plus-square',
			'angle-double-left',
			'angle-double-right',
			'angle-double-up',
			'angle-double-down',
			'angle-left',
			'angle-right',
			'angle-up',
			'angle-down',
			'desktop',
			'laptop',
			'tablet',
			'mobile-phone',
			'mobile',
			'circle-o',
			'quote-left',
			'quote-right',
			'spinner',
			'circle',
			'mail-reply',
			'reply',
			'github-alt',
			'folder-o',
			'folder-open-o',
			'smile-o',
			'frown-o',
			'meh-o',
			'gamepad',
			'keyboard-o',
			'flag-o',
			'flag-checkered',
			'terminal',
			'code',
			'reply-all',
			'mail-reply-all',
			'star-half-empty',
			'star-half-full',
			'star-half-o',
			'location-arrow',
			'crop',
			'code-fork',
			'unlink',
			'chain-broken',
			'question',
			'info',
			'exclamation',
			'superscript',
			'subscript',
			'eraser',
			'puzzle-piece',
			'microphone',
			'microphone-slash',
			'shield',
			'calendar-o',
			'fire-extinguisher',
			'rocket',
			'maxcdn',
			'chevron-circle-left',
			'chevron-circle-right',
			'chevron-circle-up',
			'chevron-circle-down',
			'html5',
			'css3',
			'anchor',
			'unlock-alt',
			'bullseye',
			'ellipsis-h',
			'ellipsis-v',
			'rss-square',
			'play-circle',
			'ticket',
			'minus-square',
			'minus-square-o',
			'level-up',
			'level-down',
			'check-square',
			'pencil-square',
			'external-link-square',
			'share-square',
			'compass',
			'toggle-down',
			'caret-square-o-down',
			'toggle-up',
			'caret-square-o-up',
			'toggle-right',
			'caret-square-o-right',
			'euro',
			'eur',
			'gbp',
			'dollar',
			'usd',
			'rupee',
			'inr',
			'cny',
			'rmb',
			'yen',
			'jpy',
			'ruble',
			'rouble',
			'rub',
			'won',
			'krw',
			'bitcoin',
			'btc',
			'file',
			'file-text',
			'sort-alpha-asc',
			'sort-alpha-desc',
			'sort-amount-asc',
			'sort-amount-desc',
			'sort-numeric-asc',
			'sort-numeric-desc',
			'thumbs-up',
			'thumbs-down',
			'youtube-square',
			'youtube',
			'xing',
			'xing-square',
			'youtube-play',
			'dropbox',
			'stack-overflow',
			'instagram',
			'flickr',
			'adn',
			'bitbucket',
			'bitbucket-square',
			'tumblr',
			'tumblr-square',
			'long-arrow-down',
			'long-arrow-up',
			'long-arrow-left',
			'long-arrow-right',
			'apple',
			'windows',
			'android',
			'linux',
			'dribbble',
			'skype',
			'foursquare',
			'trello',
			'female',
			'male',
			'gittip',
			'sun-o',
			'moon-o',
			'archive',
			'bug',
			'vk',
			'weibo',
			'renren',
			'pagelines',
			'stack-exchange',
			'arrow-circle-o-right',
			'arrow-circle-o-left',
			'toggle-left',
			'caret-square-o-left',
			'dot-circle-o',
			'wheelchair',
			'vimeo-square',
			'turkish-lira',
			'try',
			'plus-square-o',
			'space-shuttle',
			'slack',
			'envelope-square',
			'wordpress',
			'openid',
			'institution',
			'bank',
			'university',
			'mortar-board',
			'graduation-cap',
			'yahoo',
			'google',
			'reddit',
			'reddit-square',
			'stumbleupon-circle',
			'stumbleupon',
			'delicious',
			'digg',
			'pied-piper',
			'pied-piper-alt',
			'drupal',
			'joomla',
			'language',
			'fax',
			'building',
			'child',
			'paw',
			'spoon',
			'cube',
			'cubes',
			'behance',
			'behance-square',
			'steam',
			'steam-square',
			'recycle',
			'automobile',
			'car',
			'cab',
			'taxi',
			'tree',
			'spotify',
			'deviantart',
			'soundcloud',
			'database',
			'file-pdf-o',
			'file-word-o',
			'file-excel-o',
			'file-powerpoint-o',
			'file-photo-o',
			'file-picture-o',
			'file-image-o',
			'file-zip-o',
			'file-archive-o',
			'file-sound-o',
			'file-audio-o',
			'file-movie-o',
			'file-video-o',
			'file-code-o',
			'vine',
			'codepen',
			'jsfiddle',
			'life-bouy',
			'life-buoy',
			'life-saver',
			'support',
			'life-ring',
			'circle-o-notch',
			'ra',
			'rebel',
			'ge',
			'empire',
			'git-square',
			'git',
			'y-combinator-square',
			'yc-square',
			'hacker-news',
			'tencent-weibo',
			'qq',
			'wechat',
			'weixin',
			'send',
			'paper-plane',
			'send-o',
			'paper-plane-o',
			'history',
			'circle-thin',
			'header',
			'paragraph',
			'sliders',
			'share-alt',
			'share-alt-square',
			'bomb',
			'soccer-ball-o',
			'futbol-o',
			'tty',
			'binoculars',
			'plug',
			'slideshare',
			'twitch',
			'yelp',
			'newspaper-o',
			'wifi',
			'calculator',
			'paypal',
			'google-wallet',
			'cc-visa',
			'cc-mastercard',
			'cc-discover',
			'cc-amex',
			'cc-paypal',
			'cc-stripe',
			'bell-slash',
			'bell-slash-o',
			'trash',
			'copyright',
			'at',
			'eyedropper',
			'paint-brush',
			'birthday-cake',
			'area-chart',
			'pie-chart',
			'line-chart',
			'lastfm',
			'lastfm-square',
			'toggle-off',
			'toggle-on',
			'bicycle',
			'bus',
			'ioxhost',
			'angellist',
			'cc',
			'shekel',
			'sheqel',
			'ils',
			'meanpath',
			'buysellads',
			'connectdevelop',
			'dashcube',
			'forumbee',
			'leanpub',
			'sellsy',
			'shirtsinbulk',
			'simplybuilt',
			'skyatlas',
			'cart-plus',
			'cart-arrow-down',
			'diamond',
			'ship',
			'user-secret',
			'motorcycle',
			'street-view',
			'heartbeat',
			'venus',
			'mars',
			'mercury',
			'intersex',
			'transgender',
			'transgender-alt',
			'venus-double',
			'mars-double',
			'venus-mars',
			'mars-stroke',
			'mars-stroke-v',
			'mars-stroke-h',
			'neuter',
			'genderless',
			'facebook-official',
			'pinterest-p',
			'whatsapp',
			'server',
			'user-plus',
			'user-times',
			'hotel',
			'bed',
			'viacoin',
			'train',
			'subway',
			'medium',
			'yc',
			'y-combinator',
			'optin-monster',
			'opencart',
			'expeditedssl',
			'battery-4',
			'battery-full',
			'battery-3',
			'battery-three-quarters',
			'battery-2',
			'battery-half',
			'battery-1',
			'battery-quarter',
			'battery-0',
			'battery-empty',
			'mouse-pointer',
			'i-cursor',
			'object-group',
			'object-ungroup',
			'sticky-note',
			'sticky-note-o',
			'cc-jcb',
			'cc-diners-club',
			'clone',
			'balance-scale',
			'hourglass-o',
			'hourglass-1',
			'hourglass-start',
			'hourglass-2',
			'hourglass-half',
			'hourglass-3',
			'hourglass-end',
			'hourglass',
			'hand-grab-o',
			'hand-rock-o',
			'hand-stop-o',
			'hand-paper-o',
			'hand-scissors-o',
			'hand-lizard-o',
			'hand-spock-o',
			'hand-pointer-o',
			'hand-peace-o',
			'trademark',
			'registered',
			'creative-commons',
			'gg',
			'gg-circle',
			'tripadvisor',
			'odnoklassniki',
			'odnoklassniki-square',
			'get-pocket',
			'wikipedia-w',
			'safari',
			'chrome',
			'firefox',
			'opera',
			'internet-explorer',
			'tv',
			'television',
			'contao',
			'500px',
			'amazon',
			'calendar-plus-o',
			'calendar-minus-o',
			'calendar-times-o',
			'calendar-check-o',
			'industry',
			'map-pin',
			'map-signs',
			'map-o',
			'map',
			'commenting',
			'commenting-o',
			'houzz',
			'vimeo',
			'black-tie',
			'fonticons'
		);
		?>
		<style type="text/css">
			#font_awesome_icons dt { font-family:FontAwesome !important; display:inline-block;font-style:normal;font-weight:normal;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale }
			<?php foreach ( $icons as $icon ) : ?>
			.font-awesome-icon-<?php echo $icon ?>:before { content: '<?php echo $this->get_font_awesome_icon( $icon ) ?>'; }
			<?php endforeach ?>
		</style>
		<h2 style="clear:both">Font Awesome Icon Library</h2>
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
