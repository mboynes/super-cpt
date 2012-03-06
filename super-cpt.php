<?php

/*
	Plugin Name: SuperCPT
	Plugin URI: http://www.unionstreetmedia.com/
	Description: Insanely easy and attractive custom post types, custom post meta, and custom taxonomies
	Version: 0.1
	Author: Matthew Boynes
	Author URI: http://www.unionstreetmedia.com/
*/
/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if (!defined('SCPT_PLUGIN_URL'))
	define('SCPT_PLUGIN_URL', plugin_dir_url( __FILE__ ));
if (!defined('SCPT_PLUGIN_DIR'))
	define('SCPT_PLUGIN_DIR', dirname(__FILE__));

require_once SCPT_PLUGIN_DIR . '/includes/scpt-helpers.php';
require_once SCPT_PLUGIN_DIR . '/includes/class-scpt-markup.php';
require_once SCPT_PLUGIN_DIR . '/includes/class-super-custom-post-meta.php';
require_once SCPT_PLUGIN_DIR . '/includes/class-super-custom-post-type.php';
require_once SCPT_PLUGIN_DIR . '/includes/class-super-custom-taxonomy.php';
if ( is_admin() ) {
	require_once SCPT_PLUGIN_DIR . '/includes/class-scpt-admin.php';
}

class SuperCPT {

	/**
	 * Initialize the plugin and call the appropriate hook method
	 *
	 * @uses admin_hooks
	 * @author Matthew Boynes
	 */
	function __construct() {
		if (is_admin()) $this->admin_hooks();
	}

	/**
	 * Setup appropriate hooks for wp-admin
	 *
	 * @uses ScptAdmin
	 * @return void
	 * @author Matthew Boynes
	 */
	function admin_hooks() {
		$scpt_admin = new ScptAdmin;
		wp_register_style( 'supercpt.css', SCPT_PLUGIN_URL . 'css/supercpt.css', array(), '1.2' );
		wp_register_script( 'supercpt.js', SCPT_PLUGIN_URL . 'js/supercpt.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'), '1.0' );

		add_action( 'admin_enqueue_scripts', array(&$this, 'load_js_and_css') );
	}


	/**
	 * Add supercpt.css to the doc head
	 *
	 * @return void
	 * @author Matthew Boynes
	 */
	function load_js_and_css() {
		wp_enqueue_style( 'supercpt.css' );
	}

}
$scpt_plugin = new SuperCPT;

?>