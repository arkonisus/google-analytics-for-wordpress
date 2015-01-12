<?php
/**
 * This class is for the backend, extendable for all child classes
 */

if ( ! class_exists( 'Yoast_GA_Admin_Menu' ) ) {

	class Yoast_GA_Admin_Menu extends Yoast_GA_Options {

		/**
		 * The property used for storing target object (class admin)
		 *
		 * @var
		 */
		private $target_object;

		/**
		 * The dashboards disabled bool
		 *
		 * @var
		 */
		private $dashboards_disabled;

		/**
		 * Setting the target_object and adding actions
		 *
		 * @param object $target_object
		 */
		public function __construct( $target_object ) {

			$this->target_object = $target_object;

			add_action( 'admin_menu', array( $this, 'create_admin_menu' ), 10 );

			if( ! function_exists( 'is_plugin_active_for_network' ) ){
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}

			if ( is_plugin_active_for_network( GAWP_PATH ) ) {
				add_action( 'network_admin_menu', array( $this, 'create_admin_menu' ), 5 );
			}

			$this->dashboards_disabled = Yoast_GA_Settings::get_instance()->dashboards_disabled();
		}

		/**
		 * Create the admin menu
		 */
		public function create_admin_menu() {
			/**
			 * Filter: 'wpga_menu_on_top' - Allows filtering of menu location of the GA plugin, if false is returned, it moves to bottom.
			 *
			 * @api book unsigned
			 */

			// Base 64 encoded SVG image
			$icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCIgWw0KCTwhRU5USVRZIG5zX2Zsb3dzICJodHRwOi8vbnMuYWRvYmUuY29tL0Zsb3dzLzEuMC8iPg0KCTwhRU5USVRZIG5zX2V4dGVuZCAiaHR0cDovL25zLmFkb2JlLmNvbS9FeHRlbnNpYmlsaXR5LzEuMC8iPg0KCTwhRU5USVRZIG5zX2FpICJodHRwOi8vbnMuYWRvYmUuY29tL0Fkb2JlSWxsdXN0cmF0b3IvMTAuMC8iPg0KCTwhRU5USVRZIG5zX2dyYXBocyAiaHR0cDovL25zLmFkb2JlLmNvbS9HcmFwaHMvMS4wLyI+DQpdPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYWFnXzEiIHhtbG5zOng9IiZuc19leHRlbmQ7IiB4bWxuczppPSImbnNfYWk7IiB4bWxuczpncmFwaD0iJm5zX2dyYXBoczsiDQoJIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOmE9Imh0dHA6Ly9ucy5hZG9iZS5jb20vQWRvYmVTVkdWaWV3ZXJFeHRlbnNpb25zLzMuMC8iDQoJIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDAgMzEuODkiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDQwIDMxLjg5IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KPHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTQwLDEyLjUyNEM0MCw1LjYwOCwzMS40NjksMCwyMCwwQzguNTMsMCwwLDUuNjA4LDAsMTIuNTI0YzAsNS41Niw1LjI0MywxMC4yNzIsMTMuNTU3LDExLjkwN3YtNC4wNjUNCgljMCwwLDAuMDQtMS0wLjI4LTEuOTJjLTAuMzItMC45MjEtMS43Ni0zLjAwMS0xLjc2LTUuMTIxYzAtMi4xMjEsMi41NjEtOS41NjMsNS4xMjItMTAuNDQ0Yy0wLjQsMS4yMDEtMC4zMiw3LjY4My0wLjMyLDcuNjgzDQoJczEuNCwyLjcyLDQuNjQxLDIuNzJjMy4yNDIsMCw0LjUxMS0xLjc2LDQuNzE1LTIuMmMwLjIwNi0wLjQ0LDAuODQ2LTguNzIzLDAuODQ2LTguNzIzczQuMDgyLDQuNDAyLDMuNjgyLDkuMzYzDQoJYy0wLjQwMSw0Ljk2Mi00LjQ4Miw3LjIwMy02LjEyMiw5LjEyM2MtMS4yODYsMS41MDUtMi4yMjQsMy4xMy0yLjYyOSw0LjE2OGMwLjgwMS0wLjAzNCwxLjU4Ny0wLjA5OCwyLjM2MS0wLjE4NGw5LjE1MSw3LjA1OQ0KCWwtNC44ODQtNy44M0MzNS41MzUsMjIuMTYxLDQwLDE3LjcxMyw0MCwxMi41MjR6Ii8+DQo8L2c+DQo8L3N2Zz4=';

			$menu_name = is_network_admin() ? 'extensions' : 'dashboard';

			if ( $this->dashboards_disabled ) {
				$menu_name = 'settings';
			}

			// Add main page
			add_menu_page(
				__( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'General settings', 'google-analytics-for-wordpress' ), __( 'Analytics', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_' . $menu_name,
				array(
					$this->target_object,
					'load_page',
				),
				$icon_svg,
				$this->get_menu_position()
			);

			$this->add_submenu_pages();
		}

		/**
		 * Get the menu position of the Analytics item
		 *
		 * @return string
		 */
		private function get_menu_position() {
			$on_top = apply_filters( 'wpga_menu_on_top', true );

			if ( $on_top ) {
				$position = '2.00013467543';
			} else {
				$position = '100.00013467543';
			}

			// If the dashboards are disabled, force the menu item to stay at the bottom of the menu
			if ( $this->dashboards_disabled ) {
				$position = '100.00013467543';
			}

			return $position;
		}

		/**
		 * Prepares an array that can be used to add a submenu page to the Google Analytics for Wordpress menu
		 *
		 * @param $submenu_name
		 * @param $font_color
		 *
		 * @return array
		 */
		private function prepare_submenu_page( $submenu_name, $font_color ) {
			$menu_title   = $this->parse_menu_title( $submenu_name, $font_color );
			$submenu_page = array(
				'parent_slug'      => 'yst_ga_dashboard',
				'page_title'       => __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( ucfirst( $submenu_name ), 'google-analytics-for-wordpress' ),
				'menu_title'       => $menu_title,
				'capability'       => 'manage_options',
				'menu_slug'        => 'yst_ga_' . $submenu_name,
				'submenu_function' => array( $this->target_object, 'load_page' ),
			);

			if ( $this->dashboards_disabled ) {
				$submenu_page['parent_slug'] = 'yst_ga_settings';
			}

			return $submenu_page;
		}

		/**
		 * Parsing the menutitle
		 *
		 * @param string $submenu_name
		 * @param string $font_color
		 *
		 * @return string
		 */
		private function parse_menu_title( $submenu_name, $font_color ) {
			$menu_title = __( ucfirst( $submenu_name ), 'google-analytics-for-wordpress' );
			if ( ! empty( $font_color ) ) {
				$menu_title = __( '<span style="color:' . $font_color . '">' . $menu_title . '</span>', 'google-analytics-for-wordpress' );
			}

			return $menu_title;
		}

		/**
		 * Adds a submenu page to the Google Analytics for WordPress menu
		 *
		 * @param $submenu_page
		 */
		private function add_submenu_page( $submenu_page ) {
			$page             = add_submenu_page( $submenu_page['parent_slug'], $submenu_page['page_title'], $submenu_page['menu_title'], $submenu_page['capability'], $submenu_page['menu_slug'], $submenu_page['submenu_function'] );
			$is_not_dashboard = ( 'yst_ga_settings' === $submenu_page['menu_slug'] || 'yst_ga_extensions' === $submenu_page['menu_slug'] );

			$this->add_assets( $page, $is_not_dashboard );
		}

		/**
		 * Adding stylesheets and based on $is_not_dashboard maybe some more styles and scripts.
		 *
		 * @param string  $page
		 * @param boolean $is_not_dashboard
		 */
		private function add_assets( $page, $is_not_dashboard ) {
			add_action( 'admin_print_styles-' . $page, array( 'Yoast_GA_Admin_Assets', 'enqueue_styles' ) );

			add_action( 'admin_print_styles-' . $page, array( 'Yoast_GA_Admin_Assets', 'enqueue_settings_styles' ) );
			add_action( 'admin_print_scripts-' . $page, array( 'Yoast_GA_Admin_Assets', 'enqueue_scripts' ) );
			if ( ! $is_not_dashboard ) {
				Yoast_GA_Admin_Assets::enqueue_dashboard_assets();
			}
		}

		/**
		 * Prepares and adds submenu pages to the Google Analytics for Wordpress menu:
		 * - Dashboard
		 * - Settings
		 * - Extensions
		 *
		 * @return array
		 */
		private function add_submenu_pages() {
			/**
			 * Array structure:
			 *
			 * array(
			 *   $submenu_name => $font_color,
			 *   ..,
			 *   ..,
			 * )
			 *
			 * $font_color can be left empty.
			 *
			 */
			$submenu_types = array(
				'extensions' => '#f18500',
			);

			if ( ! is_network_admin() ) {
				$submenu_types = array_merge(
					array(
						'dashboard' => '',
						'settings'  => '',
					),
					$submenu_types
				);
			}

			if ( $this->dashboards_disabled ) {
				unset( $submenu_types['dashboard'] );
			}

			foreach ( $submenu_types as $submenu_name => $font_color ) {
				$submenu_page = $this->prepare_submenu_page( $submenu_name, $font_color );
				$this->add_submenu_page( $submenu_page );
			}
		}


	}

}