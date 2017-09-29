<?php
/**
 *
 * Description: Funciones para devolver info de PHP, WordPress.
 *
 * @package CL WP Info
 * License: GPL2+
 * Text Domain: cl-wp-info
 */

/**
 * Devuelve caracterísitcas del Servidor.
 *
 * @since     1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Información general del servidor.
 *
 * @since     1.0.0
 *
 * @param boolean $echo Escribir la salida o devolverla.
 */
function cl_wp_server_info( $echo = true ) {
	$html  = '';

	$html .= '<tr>';
	$html .= '<th>' . esc_html__( 'OS Server:', 'cl-wp-info' ) . '</th>';
	$html .= '<td>' . php_uname() . '</td>';
	$html .= '</tr>';

	if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['SERVER_SOFTWARE'] . '</td>';
		$html .= '</tr>';
	}

	if ( ! empty( $_SERVER['USER'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server User:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['USER'] . '</td>';
		$html .= '</tr>';
	}

	if ( ! empty( $_SERVER['SERVER_NAME'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server Name:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['SERVER_NAME'] . '</td>';
		$html .= '</tr>';
	}

	if ( ! empty( $_SERVER['SERVER_PORT'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server Port:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['SERVER_PORT'] . '</td>';
		$html .= '</tr>';
	}

	if ( ! empty( $_SERVER['SERVER_ADDR'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server Ip:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['SERVER_ADDR'] . '</td>';
		$html .= '</tr>';
	}

	if ( ! empty( $_SERVER['REQUEST_SCHEME'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server Scheme:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['REQUEST_SCHEME'] . '</td>';
		$html .= '</tr>';
	}

	if ( ! empty( $_SERVER['SERVER_PROTOCOL'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server Http Version:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['SERVER_PROTOCOL'] . '</td>';
		$html .= '</tr>';
	}

	if ( ! empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Web Server Root:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $_SERVER['DOCUMENT_ROOT'] . '</td>';
		$html .= '</tr>';
	}

	if ( $echo ) {
		echo $html;
	} else {
		return $html;
	}
} // Final de cl_wp_server_info.

/**
 * Devuelve caracterísitcas de PHP.
 *
 * @since     1.0.0
 *
 * @param boolean $echo Escribir la salida o devolverla.
 */
function cl_wp_php_info( $echo = true ) {
	$html  = '';

	$html .= '<tr>';
	$html .= '<th>' . esc_html__( 'PHP Version:', 'cl-wp-info' ) . '</th>';
	$html .= '<td>' . phpversion() . '</td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<th>' . esc_html__( 'PHP Loaded Extensions:', 'cl-wp-info' ) . '</th>';
	$html .= '<td>';
	$php_extensions = get_loaded_extensions();
	foreach ( $php_extensions as &$valor ) {
		$html .= $valor . ', ';
	}
	$html .= '</td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<th>' . esc_html__( 'PHP Webserver Interface:', 'cl-wp-info' ) . '</th>';
	$html .= '<td>' . php_sapi_name() . '</td>';
	$html .= '</tr>';

	$php_ini = ini_get_all();

	if ( ! empty( $php_ini['date.timezone'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Date/Time Zone:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['date.timezone']['global_value'] . '</div>';
		$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['date.timezone']['local_value'] . '</div>';
		$html .= '</tr>';
	}

	if ( ! empty( $php_ini['error_log'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Error Log:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['error_log']['global_value'] . '</div>';
		$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['error_log']['local_value'] . '</div>';
		$html .= '</tr>';
	}

	if ( ! empty( $php_ini['max_file_uploads'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Max File Uploads:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['max_file_uploads']['global_value'] . '</div>';
		$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['max_file_uploads']['local_value'] . '</div>';
		$html .= '</tr>';
	}

	if ( ! empty( $php_ini['post_max_size'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Post Max Size:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['post_max_size']['global_value'] . '</div>';
		$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['post_max_size']['local_value'] . '</div>';
		$html .= '</tr>';
	}

	if ( ! empty( $php_ini['upload_max_filesize'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Upload Max File Size:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['upload_max_filesize']['global_value'] . '</div>';
		$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['upload_max_filesize']['local_value'] . '</div>';
		$html .= '</tr>';
	}

	if ( ! empty( $php_ini['memory_limit'] ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Max Memory:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['memory_limit']['global_value'] . '</div>';
		$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['memory_limit']['local_value'] . '</div>';
		$html .= '</tr>';
	}

	if ( $echo ) {
		echo $html;
	} else {
		return $html;
	}
} // Final de cl_wp_php_info.

/**
 * Devuelve caracterísitcas de la Base de Datos.
 *
 * @since     1.2.0
 *
 * @param boolean $echo Escribir la salida o devolverla.
 */
function cl_wp_db_info( $echo = true ) {
	$html  = '';

	if ( defined( 'DB_NAME' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Database:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . DB_NAME . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'DB_USER' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Database Username:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . DB_USER . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'DB_PASSWORD' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Database Password:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . DB_PASSWORD . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'DB_HOST' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Database Hostname:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . DB_HOST . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'DB_CHARSET' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Database Charset to use in creating database tables:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . DB_CHARSET . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'DB_COLLATE' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Database Collate Type:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . DB_COLLATE . '</td>';
		$html .= '</tr>';
	}

	if ( $echo ) {
		echo $html;
	} else {
		return $html;
	}
} // Final de cl_wp_db_info.

/**
 * Devuelve caracterísitcas de WordPress.
 *
 * @since     1.0.0
 *
 * @param boolean $echo Escribir la salida o devolverla.
 */
function cl_wp_wordpress_info( $echo = true ) {
	$html  = '';

	$html .= '<tr>';
	$html .= '<th>' . esc_html__( 'WordPress Version:', 'cl-wp-info' ) . '</th>';
	$html .= '<td>' . get_bloginfo( 'version' ) . '</td>';
	$html .= '</tr>';

	if ( defined( 'ABSPATH' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Absolute Path to WordPress Directory:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . ABSPATH . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'WP_CONTENT_DIR' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Content Directory:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . WP_CONTENT_DIR . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'WP_HOME' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Home:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . WP_HOME . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'WP_SITEURL' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress URL:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . WP_SITEURL . '</td>';
		$html .= '</tr>';
	}

	if ( defined( 'WP_DEBUG' ) ) {
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Debug:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . WP_DEBUG . '</td>';
		$html .= '</tr>';
	}

	$args = array( 'errors' => false , 'allowed' => null );
	$temas = wp_get_themes( $args );

	$tema_actual = wp_get_theme();
	$tema_activo_textdomain = $tema_actual->get( 'TextDomain' );

	$html .= '<tr>';
	$html .= '<th>' . esc_html__( 'Themes:', 'cl-wp-info' ) . '</th>';
	$html .= '<td>';
	$html .= '<ol>';
	foreach ( $temas as $clave => $valor ) {
		$tema_nombre     = $valor->get( 'Name' );
		$tema_version    = $valor->get( 'Version' );
		//$tema_padre      = $valor->get( 'parent' );
		$tema_textdomain = $valor->get( 'TextDomain' );

		$html .= '<li>';
		$html .= $tema_nombre;
		$html .= ' <em>(' . esc_html__( 'Version', 'cl-wp-info' ) . ': ' . $tema_version . ')</em>';
		/*
		if ( ! empty( $tema_padre ) ) {
			$html .= ' <em>(' . esc_html__( 'Child Theme', 'cl-wp-info' ) . ': ' . $tema_padre . ')</em>';
		}
		*/
		if ( $tema_activo_textdomain === $tema_textdomain ) {
			$html .= ' <strong class="cl-ok-fondo">' . esc_html__( 'Active', 'cl-wp-info' ) . '</strong>';
		}
		$html .= '</li>';
	}
	$html .= '</ol>';
	$html .= '</td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<th>' . esc_html__( 'Plugins:', 'cl-wp-info' ) . '</th>';
	$html .= '<td>';
	$html .= '<ol>';
	$plugins         = get_plugins();
	$plugins_updates = get_plugin_updates();
	$plugins_activos = get_option( 'active_plugins', array() );
	foreach ( $plugins as $clave => $valor ) {
		if ( empty( $valor['PluginURI'] ) ) {
			$html .= '<li>' . $valor['Name'] . ' <em>(' . esc_html__( 'Version', 'cl-wp-info' ) . ': ' . $valor['Version'] . ')</em>';
		} else {
			$html .= '<li>' . $valor['Name'] . ' <em><a href="' . $valor['PluginURI'] . '" target="_blank" rel="noopener noreferrer">(' . esc_html__( 'Version', 'cl-wp-info' ) . ': ' . $valor['Version'] . ')</a></em>';
		}

		// Si el plugin está activado.
		if ( false !== array_search( $clave, $plugins_activos, true ) ) {
			$html .= ' <strong class="cl-ok-fondo">' . esc_html__( 'Active', 'cl-wp-info' ) . '</strong>';
		}

		// Si hay actualización para el plugin.
		// TODO: Comprobar, No funciona.
		if ( false !== in_array( $clave, $plugins_updates ) ) {
			$html .= ' <strong class="cl-warning">Actualización...</strong>';
		}

		$html .= '<br /> - <em>' . $valor['Description'] . '</em>';
		$html .= '<br /> - ' . esc_html__( 'Author:', 'cl-wp-info' ) . ' ';
		if ( empty( $valor['AuthorURI'] ) ) {
			$html .= $valor['Author'];
		} else {
			$html .= '<a href="' . $valor['AuthorURI'] . '" target="_blank" rel="noopener noreferrer">' . $valor['Author'] . '</a>';
		}

		$html .= '</li>';
	}
	$html .= '</ol>';
	$html .= '</td>';
	$html .= '</tr>';

	if ( $echo ) {
		echo $html;
	} else {
		return $html;
	}
} // Final de cl_wp_wordpress_info.
