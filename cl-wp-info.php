<?php
/**
 * Plugin Name: CL WP Info
 * Plugin URI: https://github.com/CarlosLongarela/CL-WP-Info
 * Description: Información del servidor, PHP y plugins y temas de WordPress para poder realizar fácilmente un informe previo de la web sobre la que vamos a trabajar
 * Version: 1.4.4
 * Author: Carlos Longarela
 * Author URI: https://desarrolloweb.longarela.eu/
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

define( 'CL_WP_INFO_MIN_PHP', '5.6' );
define( 'CL_WP_INFO_MIN_DB', '5.0' );
define( 'CL_WP_INFO_MIN_DB_MARIA', '10' );

define( 'CL_WP_INFO_REC_PHP', '7' );
define( 'CL_WP_INFO_REC_MYSQL', '5.6' );
define( 'CL_WP_INFO_REC_MARIA', '5.1' );

$menu_page_hook    = '';
$submenu_page_hook = '';

/**
 * Registramos el menú.
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
 * Carga de archivo CSS sólo en esta página de admin.
 *
 * @since     1.0.0
 * @param     string $hook   El hook (gancho) de la página actual.
 */
function cl_wp_info_load_custom_wp_admin_style( $hook ) {
	global $menu_page_hook, $submenu_page_hook;

	// Cargar solo en ?page=xxx del menú registrado o submenú, si no coincide salimos.
	if ( $menu_page_hook !== $hook && $submenu_page_hook !== $hook ) {
		return;
	}

	if ( $submenu_page_hook === $hook ) { // Javascript sólo en la página de submenú Tools.
		wp_enqueue_script( 'cl_wp_info_tools_admin_js', plugins_url( 'js/cl-wp-info-tools.min.js', __FILE__ ), array( 'jquery' ), null, true );
	}

	wp_enqueue_style( 'cl_wp_info_admin_css', plugins_url( 'css/cl-wp-info-admin.min.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'cl_wp_info_load_custom_wp_admin_style' );

/**
 * Carga inicial en el plugin de traducciones.
 *
 * @since     1.0.0
 */
function cl_wp_info_init() {
	load_plugin_textdomain( 'cl-wp-info', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'cl_wp_info_init' );

// Incluimos el archivo de clase con las funciones de info del sistema y WP.
require_once plugin_dir_path( __FILE__ ) . 'class-cl-wp-info.php';

// Y creamos el objeto.
$obj_info = new Cl_WP_Info();

/**
 * Función principal encargada de mostrar toda la infomación.
 *
 * @since     1.0.0
 */
function cl_wp_info_general() {
	global $obj_info;

	echo '<div class="cl-info-made-by">';
	$obj_info->cl_wp_info_made_by();
	echo '</div>';

	echo '<div class="cl-info-general">';
	$obj_info->cl_wp_info_general();
	echo '</div>';

	echo '<table class="cl-tabla-general">';

	echo '<tbody>';
	echo '<tr><th colspan="2">' . esc_html__( 'Server Info', 'cl-wp-info' ) . '</tr></th>';
	$obj_info->cl_wp_server_info();
	echo '</tbody>';

	echo '<tbody>';
	echo '<tr><th colspan="2">' . esc_html__( 'PHP Info', 'cl-wp-info' ) . '</tr></th>';
	$obj_info->cl_wp_php_info();
	echo '</tbody>';

	echo '<tbody>';
	echo '<tr><th colspan="2">' . esc_html__( 'Database Info', 'cl-wp-info' ) . '</tr></th>';
	$obj_info->cl_wp_db_info();
	echo '</tbody>';

	echo '<tbody>';
	echo '<tr><th colspan="2">' . esc_html__( 'WordPress Info', 'cl-wp-info' ) . '</tr></th>';
	$obj_info->cl_wp_wordpress_info();
	echo '</tbody>';

	echo '<tbody>';
	echo '<tr><th colspan="2">' . esc_html__( 'WordPress Themes Info', 'cl-wp-info' ) . '</tr></th>';
	$obj_info->cl_wp_wordpress_themes();
	echo '</tbody>';

	echo '<tbody>';
	echo '<tr><th colspan="2">' . esc_html__( 'WordPress Plugins Info', 'cl-wp-info' ) . '</tr></th>';
	$obj_info->cl_wp_wordpress_plugins();
	echo '</tbody>';

	echo '<tbody>';
	echo '<tr><th colspan="2">' . esc_html__( 'WordPress Javascript & CSS', 'cl-wp-info' ) . '</tr></th>';
	$obj_info->cl_wp_js_cs();
	echo '</tbody>';

	echo '</table>';

	$obj_info->cl_wp_info_donate();
}

/**
 * Función que muestra herramientras externas de medición.
 *
 * @since     1.4.0
 */
function cl_wp_info_tools() {
	global $obj_info;

	$full_url     = site_url();
	$url_parseada = wp_parse_url( $full_url );
	$dominio      = $url_parseada['host'];

	echo '<h1>' . esc_html__( 'External tools for measure page perfomance', 'cl-wp-info' ) . '</h1>';

	echo '<div id="cl-wp-info-botonera">';
	echo '<button type="button" id="cl-wpo" class="cl-botonera-btn">' . esc_html__( 'WPO', 'cl-wp-info' ) . '</button>';
	echo '<button type="button" id="cl-ttfb" class="cl-botonera-btn">' . esc_html__( 'Time to first byte', 'cl-wp-info' ) . '</button>';
	echo '<button type="button" id="cl-http2" class="cl-botonera-btn">' . esc_html__( 'HTTP/2', 'cl-wp-info' ) . '</button>';
	echo '<button type="button" id="cl-dns" class="cl-botonera-btn">' . esc_html__( 'DNS', 'cl-wp-info' ) . '</button>';
	echo '<button type="button" id="cl-gzip" class="cl-botonera-btn">' . esc_html__( 'Gzip', 'cl-wp-info' ) . '</button>';
	echo '<button type="button" id="cl-mail" class="cl-botonera-btn">' . esc_html__( 'Mail', 'cl-wp-info' ) . '</button>';
	echo '</div>';

	echo '<div id="cl-content-wpo">';
	echo '<h2 class="cl-tool-type">' . esc_html__( 'WPO (Web Performance Optimization)', 'cl-wp-info' ) . '</h2>';
	$obj_info->cl_wp_tools_wpo();
	echo '</div>';

	echo '<div id="cl-content-ttfb">';
	echo '<h2 class="cl-tool-type">' . esc_html__( 'TTFB (Time To First Byte)', 'cl-wp-info' ) . '</h2>';
	$obj_info->cl_wp_tools_ttfb();
	echo '</div>';

	echo '<div id="cl-content-http2">';
	echo '<h2 class="cl-tool-type">' . esc_html__( 'HTTP/2', 'cl-wp-info' ) . '</h2>';
	$obj_info->cl_wp_tools_http2();
	echo '</div>';

	echo '<div id="cl-content-dns">';
	echo '<h2 class="cl-tool-type">' . esc_html__( 'DNS', 'cl-wp-info' ) . '</h2>';
	$obj_info->cl_wp_tools_dns();
	echo '</div>';

	echo '<div id="cl-content-gzip">';
	echo '<h2 class="cl-tool-type">' . esc_html__( 'Gzip', 'cl-wp-info' ) . '</h2>';
	$obj_info->cl_wp_tools_gzip();
	echo '</div>';

	echo '<div id="cl-content-mail">';
	echo '<h2 class="cl-tool-type">' . esc_html__( 'Mail', 'cl-wp-info' ) . '</h2>';
	$obj_info->cl_wp_tools_mail();
	echo '</div>';

	$obj_info->cl_wp_info_donate();
}
