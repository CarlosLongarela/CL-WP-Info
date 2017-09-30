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
 *
 * Funcionalidad general del plugin.
 */
class Cl_WP_Info {
	/**
	 * Información general del servidor.
	 *
	 * @since     1.0.0
	 *
	 * @param boolean $echo Escribir la salida o devolverla.
	 */
	public function cl_wp_server_info( $echo = true ) {
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
	public function cl_wp_php_info( $echo = true ) {
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
	public function cl_wp_db_info( $echo = true ) {
		global $wpdb;
		$html  = '';

		if ( defined( 'DB_NAME' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress Database:', 'cl-wp-info' ) . '</th>';
			$html .= '<td>' . DB_NAME . '</td>';
			$html .= '</tr>';
		}

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Database prefix:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $wpdb->prefix . '</td>';
		$html .= '</tr>';

		//$html .= '<tr>';
		//$html .= '<th>' . esc_html__( 'WordPress Database version:', 'cl-wp-info' ) . '</th>';
		//$html .= '<td>' . $wpdb::db_version . '</td>';
		//$html .= '</tr>';

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
	public function cl_wp_wordpress_info( $echo = true ) {
		$html  = '';

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Version:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . get_bloginfo( 'version' ) . '</td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress URL:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . site_url() . '</td>';
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

		if ( defined( 'MEDIA_TRASH' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Trash for media:', 'cl-wp-info' ) . '</th>';
			if ( empty( MEDIA_TRASH ) ) {
				$html .= '<td>' . esc_html__( 'No', 'cl-wp-info' ) . '</td>';
			} else {
				$html .= '<td>' . esc_html__( 'Yes', 'cl-wp-info' ) . '</td>';
			}
			$html .= '</tr>';
		}

		if ( defined( 'EMPTY_TRASH_DAYS' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Empty trash each:', 'cl-wp-info' ) . '</th>';
			$html .= '<td>' . EMPTY_TRASH_DAYS . ' ' . esc_html__( 'days', 'cl-wp-info' ) . '</td>';
			$html .= '</tr>';
		}

		if ( defined( 'WP_MEMORY_LIMIT' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress memory limit:', 'cl-wp-info' ) . '</th>';
			$html .= '<td>' . WP_MEMORY_LIMIT . '</td>';
			$html .= '</tr>';
		}

		if ( defined( 'WP_MAX_MEMORY_LIMIT' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress top memory limit:', 'cl-wp-info' ) . '</th>';
			$html .= '<td>' . WP_MAX_MEMORY_LIMIT . '</td>';
			$html .= '</tr>';
		}

		if ( defined( 'WP_CACHE' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress Cache:', 'cl-wp-info' ) . '</th>';
			if ( empty( WP_CACHE ) ) {
				$html .= '<td>' . esc_html__( 'No', 'cl-wp-info' ) . '</td>';
			} else {
				$html .= '<td>' . esc_html__( 'Yes', 'cl-wp-info' ) . '</td>';
			}
			$html .= '</tr>';
		}

		if ( defined( 'MULTISITE' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress Multisite:', 'cl-wp-info' ) . '</th>';
			if ( empty( MULTISITE ) ) {
				$html .= '<td>' . esc_html__( 'No', 'cl-wp-info' ) . '</td>';
			} else {
				$html .= '<td>' . esc_html__( 'Yes', 'cl-wp-info' ) . '</td>';
			}
			$html .= '</tr>';
		}

		if ( defined( 'WP_DEBUG' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress Debug:', 'cl-wp-info' ) . '</th>';
			if ( empty( WP_DEBUG ) ) {
				$html .= '<td>' . esc_html__( 'No', 'cl-wp-info' ) . '</td>';
			} else {
				$html .= '<td>' . esc_html__( 'Yes', 'cl-wp-info' ) . '</td>';
			}
			$html .= '</tr>';
		}

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	} // Final de cl_wp_wordpress_info.

	/**
	 * Devuelve caracterísitcas de los Temas de WordPress.
	 *
	 * @since     1.2.0
	 *
	 * @param boolean $echo Escribir la salida o devolverla.
	 */
	public function cl_wp_wordpress_themes( $echo = true ) {
		$html  = '';

		$args = array(
			'errors' => false,
			'allowed' => null,
		);
		$temas = wp_get_themes( $args );

		$tema_actual = wp_get_theme();
		$tema_activo_textdomain = $tema_actual->get( 'TextDomain' );
		$tema_updates = get_theme_updates();

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Themes:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<ol>';
		foreach ( $temas as $clave_tema => $valor_tema ) {
			$tema_padre      = $valor_tema->get( 'Template' );
			$tema_textdomain = $valor_tema->get( 'TextDomain' );

			if ( empty( $valor_tema->get( 'ThemeURI' ) ) ) {
				$html .= '<li>' . $valor_tema->get( 'Name' ) . ' <em>(' . esc_html__( 'Version', 'cl-wp-info' ) . ': ' . $valor_tema->get( 'Version' ) . ')</em>';
			} else {
				$html .= '<li>' . $valor_tema->get( 'Name' ) . ' <em><a href="' . $valor_tema->get( 'ThemeURI' ) . '" target="_blank" rel="noopener noreferrer">(' . esc_html__( 'Version', 'cl-wp-info' ) . ': ' . $valor_tema->get( 'Version' ) . ')</a></em>';
			}

			if ( ! empty( $tema_padre ) ) {
				$html .= ' <em>[' . esc_html__( 'Child Theme of', 'cl-wp-info' ) . ': ' . $tema_padre . ']</em>';
			}

			if ( $tema_activo_textdomain === $tema_textdomain ) {
				$html .= ' <strong class="cl-ok-fondo">' . esc_html__( 'Active', 'cl-wp-info' ) . '</strong>';
			}

			// Si hay actualización para el tema.
			if ( false !== array_key_exists( $clave_tema, $tema_updates ) ) {
				$html .= '<br /> <span class="cl-warning">- <strong>' . esc_html__( 'Update available:', 'cl-wp-info' ) . '</strong> ';
				$html .= esc_html__( 'Version', 'cl-wp-info' ) . ': <strong>' . $tema_updates[ $clave_tema ]->update['new_version'] . '</strong>';
				$html .= '</span>';
			}

			$html .= '<br /> - <em>' . $valor_tema->get( 'Description' ) . '</em>';
			$html .= '<br /> - ' . esc_html__( 'Author:', 'cl-wp-info' ) . ' ';
			if ( empty( $valor_tema->get( 'AuthorURI' ) ) ) {
				$html .= $valor_tema->get( 'Author' ) ;
			} else {
				$html .= '<a href="' . $valor_tema->get( 'AuthorURI' )  . '" target="_blank" rel="noopener noreferrer">' . $valor_tema->get( 'Author' ) . '</a>';
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
	} // Final de cl_wp_wordpress_themes.

	/**
	 * Devuelve caracterísitcas de los Plugins de WordPress.
	 *
	 * @since     1.2.0
	 *
	 * @param boolean $echo Escribir la salida o devolverla.
	 */
	public function cl_wp_wordpress_plugins( $echo = true ) {
		$html  = '';

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Plugins:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<ol>';
		$plugins         = get_plugins();
		$plugins_updates = get_plugin_updates();
		$plugins_activos = get_option( 'active_plugins', array() );
		foreach ( $plugins as $clave_plugin => $valor_plugin ) {
			if ( empty( $valor_plugin['PluginURI'] ) ) {
				$html .= '<li>' . $valor_plugin['Name'] . ' <em>(' . esc_html__( 'Version', 'cl-wp-info' ) . ': ' . $valor_plugin['Version'] . ')</em>';
			} else {
				$html .= '<li>' . $valor_plugin['Name'] . ' <em><a href="' . $valor_plugin['PluginURI'] . '" target="_blank" rel="noopener noreferrer">(' . esc_html__( 'Version', 'cl-wp-info' ) . ': ' . $valor_plugin['Version'] . ')</a></em>';
			}

			// Si el plugin está activado.
			if ( false !== array_search( $clave_plugin, $plugins_activos, true ) ) {
				$html .= ' <strong class="cl-ok-fondo">' . esc_html__( 'Active', 'cl-wp-info' ) . '</strong>';
			}

			// Si hay actualización para el plugin.
			if ( false !== array_key_exists( $clave_plugin, $plugins_updates ) ) {
				$html .= '<br /> <span class="cl-warning">- <strong>' . esc_html__( 'Update available:', 'cl-wp-info' ) . '</strong> ';
				$html .= esc_html__( 'Version', 'cl-wp-info' ) . ': <strong>' . $plugins_updates[ $clave_plugin ]->update->new_version . '</strong>';
				$html .= ' <em>(' . esc_html__( 'Compatible with WordPress', 'cl-wp-info' ) . ': ' . $plugins_updates[ $clave_plugin ]->update->tested . ')</em>';
				$html .= '</span>';
			}

			$html .= '<br /> - <em>' . $valor_plugin['Description'] . '</em>';
			$html .= '<br /> - ' . esc_html__( 'Author:', 'cl-wp-info' ) . ' ';
			if ( empty( $valor_plugin['AuthorURI'] ) ) {
				$html .= $valor_plugin['Author'];
			} else {
				$html .= '<a href="' . $valor_plugin['AuthorURI'] . '" target="_blank" rel="noopener noreferrer">' . $valor_plugin['Author'] . '</a>';
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
	} // Final de cl_wp_wordpress_plugins.
}
