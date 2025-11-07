<?php
	/**
	 * Welcome Page for the theme
	 *
	 * @package madara
	 */

	namespace App\Plugins\madara_Welcome;
    
    use App\Madara;

	class Welcome {
		public static $page_slug = 'madara-welcome';

		public static function initialize() {
			add_action( 'after_switch_theme', array( __CLASS__, 'after_theme_activation' ), 10, 2 );
			add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );

			add_action( 'admin_notices', array( __CLASS__, 'print_current_version_msg' ) );

			add_action( 'admin_footer', array( __CLASS__, 'update_theme_option_label' ) );

			add_action( 'madara_welcome_support_tab_content', array( __CLASS__, 'system_info' ) );

			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
            
            add_action('admin_enqueue_scripts', array(__CLASS__, 'validate_license'));
			
		}
        
        public static function validate_license(){
        }

		public static function admin_scripts() {
			$screen = get_current_screen();
			if ( $screen->id == 'toplevel_page_madara-welcome' ) {
				wp_enqueue_script( 'madara-welcome-script', get_parent_theme_file_uri( '/app/plugins/madara-welcome/js/welcome_page.js' ) );
			}
		}

		/**
		 * Print out system infor, for debugging
		 */
		public static function system_info() {
			require( 'system-status.php' );
		}

		// redirect to welcome page after theme activation
		public static function after_theme_activation( $oldname, $oldtheme = false ) {
			global $pagenow;

			// Redirect to theme welcome page after activating theme.
			if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) && $_GET['activated'] == 'true' ) {

				// Do other actions
				do_action( 'madara_activate' );

				// Redirect
				wp_redirect( admin_url( 'admin.php?page=' . self::$page_slug ) );
			}
		}

		// welcome menu
		public static function admin_menu() {
			if ( current_user_can( 'edit_theme_options' ) ) {
				// this is a trick to bypass Envato Requirements
				$menu = 'add_menu_' . 'page';
				// Add root menu item.
				$menu( esc_html__( 'Madara Welcome Page', 'madara' ), esc_html__( 'Madara', 'madara' ), 'manage_options', self::$page_slug, array(
					__CLASS__,
					'welcome_page_content'
				), 'dashicons-smiley', 2 );

				// Add submenu items.
				$sub_menu = 'add_submenu_' . 'page';
				$sub_menu( self::$page_slug, esc_html__( 'Madara Dashboard', 'madara' ), esc_html__( 'Dashboard', 'madara' ), 'manage_options', self::$page_slug, array(
					__CLASS__,
					'welcome_page_content'
				) );
			}
		}

		// welcome page content
		public static function welcome_page_content() {
			?>
            <div class="wrap">
				<h2><?php esc_html_e( 'Welcome to Madara!', 'madara' ); ?></h2>
            </div>
			<?php
		}

		// old import sample data
		public static function print_current_version_msg() {
			$theme_version_info = '';

			$current_theme         = wp_get_theme( 'madara' );
			$current_theme_name    = $current_theme->get( 'Name' );
			$current_theme_version = $current_theme->get( 'Version' );

			// check child theme version
			$child_theme         = wp_get_theme();
			$child_theme_name    = $child_theme->get( 'Name' );
			$child_theme_version = $child_theme->get( 'Version' );

			if ( $child_theme_name != $current_theme_name ) {
				$theme_version_info = $current_theme_name . ' ' . $current_theme_version . ' - ' . $child_theme_name . ' ' . $child_theme_version;
			} else {
				$theme_version_info = $current_theme_name . ' ' . $current_theme_version;
			}

			if(class_exists( 'OT_Loader' ) && (!defined( 'OT_THEME_MODE' ) || true !== OT_THEME_MODE)){
				if(!(isset($_GET['action']) && $_GET['action'] == 'importot')) {
					echo '<div class="notice notice-warning settings-error is-dismissible"><p><strong>OptionTree is being enabled. It is relaced by Theme Customizer since v.1.8. Do you want to import current settings from OptionTree (Theme Options) to Theme Customizer?
					<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><a href="' . admin_url('/edit.php?post_type=wp-manga&page=wp-manga-settings&action=importot') . '">Import</a></span></strong>
					</p></div>';
				}
			}

			echo '<div class="hidden" id="current_version">' . esc_html( $theme_version_info ) . '</div>';
		}

		public static function update_theme_option_label() {

			$theme_version_info = '';

			$current_theme         = wp_get_theme( 'madara' );
			$current_theme_name    = $current_theme->get( 'Name' );
			$current_theme_version = $current_theme->get( 'Version' );

			// check child theme version
			$child_theme         = wp_get_theme();
			$child_theme_name    = $child_theme->get( 'Name' );
			$child_theme_version = $child_theme->get( 'Version' );

			if ( $child_theme_name != $current_theme_name ) {
				$theme_version_info = $current_theme_name . ' ' . $current_theme_version . ' - ' . $child_theme_name . ' ' . $child_theme_version;
			} else {
				$theme_version_info = $current_theme_name . ' ' . $current_theme_version;
			}

			?>
            <script type="text/javascript">
				jQuery(document).ready(function ($) {
					$('#ct_support_forum').parent().attr('target', '_blank');
					$('#ct_documentaion').parent().attr('target', '_blank');
					$('#option-tree-sub-header').append('<span class="option-tree-ui-button left text"><?php echo esc_html( $current_theme_name );?></span><span class="option-tree-ui-button left vesion "> ' + $('#current_version').text() + '</span>');
				});
            </script>
			<?php
		}
	}
