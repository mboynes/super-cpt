<?php

class SCPT_Admin {

	function __construct() {
		# Setup WordPress hooks
		add_action( 'admin_menu', array( $this, 'super_cpt_menu_menu' ) );
	}

	function super_cpt_menu_menu() {
		// Add new admin menu and save returned page hook
		add_management_page( __( 'SuperCPT' ), __( 'SuperCPT' ), 'manage_options', 'super_cpt', array( $this, 'scpt_site_settings_page' ) );
	}

	function scpt_site_settings_page() {
		global $scpt_plugin;
		if ( !current_user_can( 'manage_options' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

		require_once SCPT_PLUGIN_DIR . '/includes/class-scpt-font-awesome.php';
		?>
		<div class="wrap">
			<div id="icon-scpt" class="icon32"><br /></div>
			<h2>SuperCPT Settings</h2>

			<h3 class="nav-tab-wrapper">
				<a class="nav-tab nav-tab-active" href="#scpt_icons">Icons</a>
			</h3>

			<div id="scpt_icons">
				<?php do_action( 'scpt_plugin_icon_demos' ) ?>
			</div>
		</div>
		<?php
	}

}


?>