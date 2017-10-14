<?php
/**
 * Plugin Name: CL WP Info
 * Plugin URI: https://github.com/CarlosLongarela/CL-WP-Info
 * Description: Información del servidor, PHP y plugins y temas de WordPress para poder realizar fácilmente un informe previo de la web sobre la que vamos a trabajar
 * Version: 1.2
 * Author: Carlos Longarela
 * Author URI: https://desarrolloweb.longarela.eu/
 *
 * @package CL WP Info
 * License: GPL2+
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
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

define( 'CL_WP_INFO_MIN_PHP', '5.2.4' );
define( 'CL_WP_INFO_MIN_DB', '5.0' );
define( 'CL_WP_INFO_MIN_DB_MARIA', '10' );

define( 'CL_WP_INFO_REC_PHP', '7' );
define( 'CL_WP_INFO_REC_MYSQL', '5.6' );
define( 'CL_WP_INFO_REC_MARIA', '5.1' );

/**
 * Registramos el menú.
 *
 * @since     1.0.0
 */
function cl_wp_info_add_menu_page() {
	add_menu_page(
		esc_html__( 'CL WP Info', 'cl-wp-info' ),
		esc_html__( 'CL WP Info', 'cl-wp-info' ),
		'manage_options',
		'cl-wp-info',
		'cl_wp_info_general',
		'dashicons-visibility',
		3
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
	// Cargar solo en ?page=toplevel_page_cl-wp-info.
	if ( 'toplevel_page_cl-wp-info' !== $hook ) {
			return;
	}
	wp_enqueue_style( 'custom_wp_admin_css', plugins_url( 'css/cl-wp-info-admin.min.css', __FILE__ ) );
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

// Incluimos el archivo con las funciones de info del sistema y WP.
require_once plugin_dir_path( __FILE__ ) . 'class-cl-wp-info.php';

/**
 * Función principal encargada de mostrar toda la infomación.
 *
 * @since     1.0.0
 */
function cl_wp_info_general() {
	$obj_info = new Cl_WP_Info();

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

	echo '<div class="cl-no-print cl-donate updated">';
	echo '<p>' . esc_html__( "If this plugin is useful for you, maybe you'd like to collaborate with its development and invite me to a coffe or a beer", 'cl-wp-info' ) . '</p>';
	echo '<p><a class="cl-donate-btn" href="https://www.paypal.me/CarlosLongarela" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Yes, of course :)', 'cl-wp-info' ) . '</a></p>';
	echo '<p>' . esc_html__( 'Or maybe you can donate with Bitcoin', 'cl-wp-info' ) . ' <em>13m3ARiWhLcG7hSswZPmrqNTKaPJbaSvro</em> ' . esc_html__( 'or Ether', 'cl-wp-info' ) . ' <em>0x58cd21317d86dBC6374B518312eB27571abE7638</em></p>';
	echo '<p class="cl-note">* ' . esc_html__( 'This note will not be printed if you select Print menu and send to printer device or pdf (recomended option)', 'cl-wp-info' ) . '</p>';
	echo '</div>';
}
