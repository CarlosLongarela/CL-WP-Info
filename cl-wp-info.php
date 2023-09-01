<?php
/**
 * Plugin Name: CL WP Info
 * Plugin URI: https://github.com/CarlosLongarela/CL-WP-Info
 * Description: Server information, PHP and WordPress plugins and themes so that we can easily preview the website we are going to work on
 * Version: 1.4.17
 * Author: Carlos Longarela
 * Author URI: https://tabernawp.com/
 *
 * @package CL WP Info
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cl-wp-info
 *
 * CL WP Info is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * CL WP Info is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CL WP Info. If not, see https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'CL_WP_INFO_MIN_PHP', '5.6.20' );
define( 'CL_WP_INFO_MIN_DB', '5.0.15' );
define( 'CL_WP_INFO_MIN_DB_MARIA', '10' );

define( 'CL_WP_INFO_REC_PHP', '7.4' );
define( 'CL_WP_INFO_REC_MYSQL', '5.6' );
define( 'CL_WP_INFO_REC_MARIA', '10.1' );

$menu_page_hook    = '';
$submenu_page_hook = '';

/**
 * Menu register.
 *
 * @since     1.0.0
 */
function cl_wp_info_add_menu_page() {
	global $menu_page_hook, $submenu_page_hook;

	$menu_page_hook = add_menu_page(
		esc_html__( 'CL WP Info', 'cl-wp-info' ),
		esc_html__( 'CL WP Info', 'cl-wp-info' ),
		'manage_options',
		'cl-wp-info',
		'cl_wp_info_general',
		'dashicons-visibility',
		76
	);

	$submenu_page_hook = add_submenu_page(
		'cl-wp-info',
		esc_html__( 'External Tools', 'cl-wp-info' ),
		esc_html__( 'External Tools', 'cl-wp-info' ),
		'manage_options',
		'cl-wp-info-tools',
		'cl_wp_info_tools'
	);
}
add_action( 'admin_menu', 'cl_wp_info_add_menu_page' );

/**
 * Load CSS files only in this admin page.
 *
 * @since     1.0.0
 * @param     string $hook   El hook (gancho) de la página actual.
 */
function cl_wp_info_load_custom_wp_admin_style( $hook ) {
	global $menu_page_hook, $submenu_page_hook;

	// Load only in ?page=xxx from registered menu or submenu, if is different, we exit.
	if ( $menu_page_hook !== $hook && $submenu_page_hook !== $hook ) {
		return;
	}

	if ( $submenu_page_hook === $hook ) { // Javascript only in submenu page Tools.
		wp_enqueue_script( 'cl_wp_info_tools_admin_js', plugins_url( 'js/cl-wp-info-tools.min.js', __FILE__ ), array( 'jquery' ), null, true ); // phpcs:ignore
	}

	wp_enqueue_style( 'cl_wp_info_admin_css', plugins_url( 'css/cl-wp-info-admin.min.css', __FILE__ ) ); // phpcs:ignore
}
add_action( 'admin_enqueue_scripts', 'cl_wp_info_load_custom_wp_admin_style' );

/**
 * Initial plugin translations load.
 *
 * @since     1.0.0
 */
function cl_wp_info_init() {
	load_plugin_textdomain( 'cl-wp-info', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'cl_wp_info_init' );

// Include class file with system info and WP info.
require_once plugin_dir_path( __FILE__ ) . 'class-cl-wp-info.php';

// And we create object.
$obj_info = new Cl_WP_Info();

/**
 * Principal functions for show all information.
 *
 * @since     1.0.0
 */
function cl_wp_info_general() {
	global $obj_info;
	?>

	<div class="cl-info-made-by"><?php $obj_info->cl_wp_info_made_by(); ?></div>

	<div class="cl-info-general"><?php $obj_info->cl_wp_info_general(); ?></div>

	<table class="cl-tabla-general">
		<tbody>
			<tr>
				<th colspan="2"><?php esc_html_e( 'Server Info', 'cl-wp-info' ); ?></th>
			</tr>
			<?php $obj_info->cl_wp_server_info(); ?>

			<tr>
				<th colspan="2"><?php esc_html_e( 'PHP Info', 'cl-wp-info' ); ?></th>
			</tr>
			<?php $obj_info->cl_wp_php_info(); ?>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Database Info', 'cl-wp-info' ); ?></th>
			</tr>
			<?php $obj_info->cl_wp_db_info(); ?>

			<tr>
				<th colspan="2"><?php esc_html_e( 'WordPress Info', 'cl-wp-info' ); ?></th>
			</tr>
			<?php $obj_info->cl_wp_wordpress_info(); ?>

			<tr>
				<th colspan="2"><?php esc_html_e( 'WordPress Themes Info', 'cl-wp-info' ); ?></th>
			</tr>
			<?php $obj_info->cl_wp_wordpress_themes(); ?>

			<tr>
				<th colspan="2"><?php esc_html_e( 'WordPress Plugins Info', 'cl-wp-info' ); ?></th>
			</tr>
			<?php $obj_info->cl_wp_wordpress_plugins(); ?>

			<tr>
				<th colspan="2"><?php esc_html_e( 'WordPress Javascript & CSS', 'cl-wp-info' ); ?></th>
			</tr>
			<?php $obj_info->cl_wp_js_cs(); ?>
		</tbody>
	</table>

	<?php
	$obj_info->cl_wp_info_donate();
}

/**
 * Show external tools for measurements.
 *
 * @since     1.4.0
 */
function cl_wp_info_tools() {
	global $obj_info;
	?>

	<h1><?php esc_html_e( 'External tools for measure page perfomance', 'cl-wp-info' ); ?></h1>

	<div id="cl-wp-info-botonera">
		<button type="button" id="cl-wpo" class="cl-botonera-btn"><?php esc_html_e( 'WPO', 'cl-wp-info' ); ?></button>
		<button type="button" id="cl-ttfb" class="cl-botonera-btn"><?php esc_html_e( 'Time to first byte', 'cl-wp-info' ); ?></button>
		<button type="button" id="cl-http2" class="cl-botonera-btn"><?php esc_html_e( 'HTTP/2', 'cl-wp-info' ); ?></button>
		<button type="button" id="cl-dns" class="cl-botonera-btn"><?php esc_html_e( 'DNS', 'cl-wp-info' ); ?></button>
		<button type="button" id="cl-gzip" class="cl-botonera-btn"><?php esc_html_e( 'Gzip', 'cl-wp-info' ); ?></button>
		<button type="button" id="cl-mail" class="cl-botonera-btn"><?php esc_html_e( 'Mail', 'cl-wp-info' ); ?></button>
	</div>

	<div id="cl-content-wpo">
		<h2 class="cl-tool-type"><?php esc_html_e( 'WPO (Web Performance Optimization)', 'cl-wp-info' ); ?></h2>
		<?php $obj_info->cl_wp_tools_wpo(); ?>
	</div>

	<div id="cl-content-ttfb">
		<h2 class="cl-tool-type"><?php esc_html_e( 'TTFB (Time To First Byte)', 'cl-wp-info' ); ?></h2>
		<?php $obj_info->cl_wp_tools_ttfb(); ?>
	</div>

	<div id="cl-content-http2">
		<h2 class="cl-tool-type"><?php esc_html_e( 'HTTP/2', 'cl-wp-info' ); ?></h2>
		<?php $obj_info->cl_wp_tools_http2(); ?>
	</div>

	<div id="cl-content-dns">
		<h2 class="cl-tool-type"><?php esc_html_e( 'DNS', 'cl-wp-info' ); ?></h2>
		<?php $obj_info->cl_wp_tools_dns(); ?>
	</div>

	<div id="cl-content-gzip">
		<h2 class="cl-tool-type"><?php esc_html_e( 'Gzip', 'cl-wp-info' ); ?></h2>
		<?php $obj_info->cl_wp_tools_gzip(); ?>
	</div>

	<div id="cl-content-mail">
		<h2 class="cl-tool-type"><?php esc_html_e( 'Mail', 'cl-wp-info' ); ?></h2>
		<?php $obj_info->cl_wp_tools_mail(); ?>
	</div>

	<?php
	$obj_info->cl_wp_info_donate();
}
