<?php

class SCPT_Admin {

	function __construct() {
		# Setup WordPress hooks
		add_action( 'admin_menu', array( &$this, 'super_cpt_menu_menu' ) );
	}

	function super_cpt_menu_menu() {
		// Add new admin menu and save returned page hook
		add_management_page( __( 'SuperCPT' ), __( 'SuperCPT' ), 'manage_options', 'super_cpt', array( &$this, 'scpt_site_settings_page' ) );
	}

	function scpt_site_settings_page() {
		global $scpt_plugin;
		if ( !current_user_can( 'manage_options' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		$images = glob( SCPT_PLUGIN_DIR . '/images/16/*.png' );
		?>
		<div class="wrap">
			<div id="icon-scpt" class="icon32"><br /></div>
			<h2>SuperCPT Settings</h2>

			<script type="text/javascript">
				jQuery(function($){
					$('.nav-tab-wrapper .nav-tab').click(function(e){
						e.preventDefault();
						$($('.nav-tab-active').removeClass('nav-tab-active').attr('href')).hide();
						$($(this).addClass('nav-tab-active').attr('href')).fadeIn('fast');
					});
				});
			</script>

			<h3 class="nav-tab-wrapper">
				<a class="nav-tab nav-tab-active" href="#scpt_icons">Icons</a>
				<?php /* <a class="nav-tab" href="#slug">Label</a> */ ?>
			</h3>

			<div id="scpt_icons">
				<div id="glyphicons">
				<?php foreach ( $images as $image ) : preg_match( '/^.*\/([^\/]+)\.png$/', $image, $path ); ?>
					<dl class="glyphicon"><dt class="glyphicons_<?php echo $path[1] ?>"></dt><dd><?php echo $path[1] ?></dd></dl>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}

}


?>