<?php
/**
 * Admin settings
 *
 * @package SocialShareForDevs
 */

namespace SocialShareForDevs;

/**
 * Settings class
 */
class Settings {

	/**
	 *     Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		add_action( 'admin_init', array( $this, 'main_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Add Admin Menu Tab
	 */
	public function admin_page() {
		$main_page_hook = add_menu_page(
			'Social Share for Devs',
			__( 'Social Share for Devs', 'social-share-for-devs' ),
			'manage_options',
			'ssfd-settings-page',
			array(
				$this,
				'ssfd_page',
			),
			'data:image/svg+xml;base64, PHN2ZyB3aWR0aD0iMTIzIiBoZWlnaHQ9IjEyMyIgdmlld0JveD0iMCAwIDEyMyAxMjMiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNNjEuNSAxMTIuOTIzQzg5LjkwMDIgMTEyLjkyMyAxMTIuOTIzIDg5LjkwMDIgMTEyLjkyMyA2MS41QzExMi45MjMgMzMuMDk5OCA4OS45MDAyIDEwLjA3NjkgNjEuNSAxMC4wNzY5QzMzLjA5OTggMTAuMDc2OSAxMC4wNzY5IDMzLjA5OTggMTAuMDc2OSA2MS41QzEwLjA3NjkgNzAuNjUyNiAxMi40NTk1IDc5LjIxOTIgMTYuNjMxIDg2LjY0NDJDMTguMDEzOSA4OS4xMDU2IDE4LjU1MzUgOTIuMDQ2OSAxNy45OTU3IDk0Ljk3MDlMMTUuMzk2OCAxMDguNTk0TDI2LjQ5ODcgMTA1LjE5M0MyOS44MTg3IDEwNC4xNzUgMzMuMzA4OSAxMDQuNjQxIDM2LjE2ODkgMTA2LjI2M0M0My42MzcxIDExMC41IDUyLjI3MDggMTEyLjkyMyA2MS41IDExMi45MjNaTTI5LjM2NzcgMTE0LjU1OEMzMC4wMjU4IDExNC4zNTYgMzAuNzM2OSAxMTQuNDQzIDMxLjMzNTUgMTE0Ljc4M0M0MC4yMzg3IDExOS44MzQgNTAuNTMyNSAxMjIuNzE4IDYxLjUgMTIyLjcxOEM5NS4zMDk3IDEyMi43MTggMTIyLjcxOCA5NS4zMDk3IDEyMi43MTggNjEuNUMxMjIuNzE4IDI3LjY5MDMgOTUuMzA5NyAwLjI4MjA0NyA2MS41IDAuMjgyMDQ3QzI3LjY5MDMgMC4yODIwNDcgMC4yODIwNDcgMjcuNjkwMyAwLjI4MjA0NyA2MS41QzAuMjgyMDQ3IDcyLjM3NjIgMy4xMTgzMyA4Mi41ODk5IDguMDkxNiA5MS40NDE5QzguMzgwNjkgOTEuOTU2NCA4LjQ4NDkzIDkyLjU1NTYgOC4zNzQzMiA5My4xMzU0TDMuNDkxOTUgMTE4LjcyOEMzLjE0NjM4IDEyMC41MzkgNC44NTEzNCAxMjIuMDY4IDYuNjE0NTQgMTIxLjUyOEwyOS4zNjc3IDExNC41NThaIiBmaWxsPSJ1cmwoI3BhaW50MF9saW5lYXJfMV85MCkiLz4KPGNpcmNsZSBjeD0iMzkuNDYxNSIgY3k9IjU0LjE1MzkiIHI9IjcuMzQ2MTUiIGZpbGw9IiM4NjI4QjYiLz4KPGNpcmNsZSBjeD0iODMuNTM4MyIgY3k9IjU0LjE1MzgiIHI9IjcuMzQ2MTUiIGZpbGw9IiM4NjI4QjYiLz4KPHBhdGggZD0iTTc2LjY0MDkgNzEuMjk0OUM3Ny43NDU0IDcxLjI5NDkgNzguNjUyOSA3Mi4xOTMzIDc4LjUyNDMgNzMuMjkwM0M3OC4zNDA4IDc0Ljg1NiA3Ny45NDE4IDc2LjM5MjEgNzcuMzM2MSA3Ny44NTQ0Qzc2LjQ3NDcgNzkuOTM0MSA3NS4yMTIxIDgxLjgyMzcgNzMuNjIwNCA4My40MTU0QzcyLjAyODcgODUuMDA3MSA3MC4xMzkxIDg2LjI2OTcgNjguMDU5NCA4Ny4xMzExQzY1Ljk3OTggODcuOTkyNSA2My43NTA4IDg4LjQzNTkgNjEuNDk5OCA4OC40MzU5QzU5LjI0ODggODguNDM1OSA1Ny4wMTk5IDg3Ljk5MjUgNTQuOTQwMiA4Ny4xMzExQzUyLjg2MDYgODYuMjY5NyA1MC45NzEgODUuMDA3MSA0OS4zNzkzIDgzLjQxNTRDNDcuNzg3NiA4MS44MjM3IDQ2LjUyNSA3OS45MzQxIDQ1LjY2MzYgNzcuODU0NEM0NS4wNTc5IDc2LjM5MjEgNDQuNjU4OSA3NC44NTYgNDQuNDc1NCA3My4yOTAzQzQ0LjM0NjggNzIuMTkzMyA0NS4yNTQyIDcxLjI5NDkgNDYuMzU4OCA3MS4yOTQ5TDYxLjQ5OTggNzEuMjk0OUg3Ni42NDA5WiIgZmlsbD0iIzg2MjhCNiIvPgo8ZGVmcz4KPGxpbmVhckdyYWRpZW50IGlkPSJwYWludDBfbGluZWFyXzFfOTAiIHgxPSI2MS41IiB5MT0iMC4yODIwNDciIHgyPSI2MS41IiB5Mj0iMTIyLjcxOCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgo8c3RvcCBzdG9wLWNvbG9yPSIjRkU1RDk3Ii8+CjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI0JENUVGNCIvPgo8L2xpbmVhckdyYWRpZW50Pgo8L2RlZnM+Cjwvc3ZnPgo=',
			'99.9'
		);

		add_action( "load-{$main_page_hook}", array( $this, 'ssfd_page_assets' ) );
	}

	/**
	 * Register Fields for Settings Page
	 */
	public function main_settings() {
		add_settings_section( 'ssfd_first_section', null, null, 'ssfd-settings-page' );
		add_settings_section( 'ssfd_second_section', null, null, 'ssfd-settings-page' );

		add_settings_field( 'ssfd_facebook', 'Facebook', array( $this, 'ssfd_checkbox_html' ), 'ssfd-settings-page', 'ssfd_first_section', array( 'theName' => 'ssfd_facebook' ) );
		register_setting(
			'ssfd_plugin',
			'ssfd_facebook',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0',
			)
		);

		add_settings_field( 'ssfd_twitter', 'X (Twitter)', array( $this, 'ssfd_checkbox_html' ), 'ssfd-settings-page', 'ssfd_first_section', array( 'theName' => 'ssfd_twitter' ) );
		register_setting(
			'ssfd_plugin',
			'ssfd_twitter',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0',
			)
		);

		add_settings_field( 'ssfd_linkedin', 'LinkedIn', array( $this, 'ssfd_checkbox_html' ), 'ssfd-settings-page', 'ssfd_first_section', array( 'theName' => 'ssfd_linkedin' ) );
		register_setting(
			'ssfd_plugin',
			'ssfd_linkedin',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0',
			)
		);

		add_settings_field( 'ssfd_whatsapp', 'Whatsapp', array( $this, 'ssfd_checkbox_html' ), 'ssfd-settings-page', 'ssfd_first_section', array( 'theName' => 'ssfd_whatsapp' ) );
		register_setting(
			'ssfd_plugin',
			'ssfd_whatsapp',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0',
			)
		);

		add_settings_field( 'ssfd_pinterest', 'Pinterest', array( $this, 'ssfd_checkbox_html' ), 'ssfd-settings-page', 'ssfd_first_section', array( 'theName' => 'ssfd_pinterest' ) );
		register_setting(
			'ssfd_plugin',
			'ssfd_pinterest',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0',
			)
		);

		add_settings_field( 'ssfd_email', 'Email', array( $this, 'ssfd_checkbox_html' ), 'ssfd-settings-page', 'ssfd_first_section', array( 'theName' => 'ssfd_email' ) );
		register_setting(
			'ssfd_plugin',
			'ssfd_email',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0',
			)
		);

		add_settings_field( 'ssfd_type', __( 'Type of Buttons', 'social-share-for-devs' ), array( $this, 'ssfd_select_html' ), 'ssfd-settings-page', 'ssfd_second_section' );
		register_setting(
			'ssfd_plugin',
			'ssfd_type',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			)
		);

		add_settings_field( 'ssfd_class', __( 'Add custom class to buttons', 'social-share-for-devs' ), array( $this, 'ssfd_input_html' ), 'ssfd-settings-page', 'ssfd_second_section' );
		register_setting(
			'ssfd_plugin',
			'ssfd_class',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			)
		);
	}

	/**
	 * BackEnd Assets
	 */
	public function admin_scripts() {
			wp_enqueue_style(
				'ssfd-admin-styles',
				plugins_url( '../assets/build/wc-social-share.min.css', __FILE__ ),
				null,
				filemtime( plugin_dir_path( __FILE__ ) . '../assets/build/wc-social-share.min.css' )
			);
	}

	/**
	 * Social buttons page Assets
	 */
	public function ssfd_page_assets() {
		wp_enqueue_style( 'back-style', plugin_dir_url( __FILE__ ) . '../assets/build/wc-social-share.min.css', null, $ver = false );
		wp_enqueue_style( 'front-style', plugin_dir_url( __FILE__ ) . '../assets/build/wc-social-share.min.css', null, $ver = false );
	}

	/**
	 * Fields HTML
	 * Reusable checkbox function
	 *
	 * @param array $args arguments array.
	 */
	public function ssfd_checkbox_html( $args ) {
		$args;
		include plugin_dir_path( __FILE__ ) . '../templates/admin/admin.php';
	}

	/**
	 * Select HTML
	 * Options for type of buttons
	 */
	public function ssfd_select_html() {
		include plugin_dir_path( __FILE__ ) . '../templates/admin/select.php';
	}

	/**
	 * Input HTML
	 * Input to add custom class to buttons
	 */
	public function ssfd_input_html() {
		include plugin_dir_path( __FILE__ ) . '../templates/admin/input.php';
	}

	/**
	 * Forms HTML
	 */
	public function ssfd_page() {
		include plugin_dir_path( __FILE__ ) . '../templates/admin/forms.php';
	}
}
