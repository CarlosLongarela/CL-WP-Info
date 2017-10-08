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

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 *
 * Funcionalidad general del plugin.
 */
class Cl_WP_Info {

	/**
	 * Cadena de texto con la versión de WordPress
	 *
	 * @var string
	 */
	private $wp_version = '';

	/**
	 * Objeto con los datos de actualización del core de WordPress
	 *
	 * @var object
	 */
	private $wp_update_core = false;

	/**
	 * Objeto con los números de posts
	 *
	 * @var object
	 */
	private $n_posts;

	/**
	 * Objeto con los números de páginas
	 *
	 * @var object
	 */
	private $n_pages;

	/**
	 * Array con el número de usuarios
	 *
	 * @var array
	 */
	private $n_users    = array();

	/**
	 * Número de comentarios
	 *
	 * @var int
	 */
	private $n_comments = 0;

	/**
	 * Objeto con los números de medios
	 *
	 * @var object
	 */
	private $n_media;

	/**
	 * Cadena de texto con el locale de WordPress
	 *
	 * @var string
	 */
	private $wp_locale;

	/**
	 * Booleano indicando si $wp_update_core es un objeto
	 *
	 * @var bool
	 */
	private $wp_update_core_is_object = false;

	/**
	 * Cadena de texto con la versión de la BD de WordPress
	 *
	 * @var string
	 */
	private $db_version;

	/**
	 * Constructor desde el que recuperamos valores a utilizar posteriormente.
	 *
	 * @since     1.2.0
	 */
	public function __construct() {
		global $wpdb;

		$this->wp_version     = get_bloginfo( 'version' );
		$this->n_posts        = wp_count_posts();
		$this->n_pages        = wp_count_posts( 'page' );
		$this->n_users        = count_users();
		$this->n_comments     = get_comments( array( 'count' => true ) );
		$this->n_media        = wp_count_attachments();
		$this->wp_locale      = get_locale();

		$this->db_version     = $wpdb->get_var( 'select version();' );

		$sql = 'SELECT option_value FROM ' . $wpdb->prefix . "options WHERE option_name = '_site_transient_update_core'";
		$this->wp_update_core = maybe_unserialize( $wpdb->get_var( $sql ) );

		$this->wp_update_core_is_object = is_object( $this->wp_update_core );
	}

	/**
	 * Información general del WP y su estado.
	 *
	 * @since     1.0.0
	 *
	 * @param boolean $echo Escribir la salida o devolverla.
	 */
	public function cl_wp_info_general( $echo = true ) {
		$html  = '';

		// WordPress Version.
		$html .= '<p><strong>' . esc_html__( 'WordPress Version:', 'cl-wp-info' ) . ' ' . $this->wp_version . '</strong>';

		if ( $this->wp_update_core_is_object ) {
			if ( $this->wp_update_core->updates[0]->version === $this->wp_update_core->updates[0]->current ) {
				$html .= ' <span class="cl-ok">(' . esc_html__( 'You have latest available version', 'cl-wp-info' ) . ').</span>';
			} else {
				$html .= ' <span class="cl-error">(' . esc_html__( 'There is a new WordPress version available', 'cl-wp-info' ) . ': ' . $this->wp_update_core->updates[0]->version . ').</span>';
			}
		}
		$html .= '</p>';

		if ( $this->wp_update_core_is_object ) {
			$fecha_check = get_date_from_gmt( date( 'Y-m-d H:i:s', $this->wp_update_core->last_checked ), get_option( 'date_format' ) . ' - ' . get_option( 'time_format' ) );
			$html .= '<p>';
			$html .= esc_html__( 'Last WordPress version checked:', 'cl-wp-info' ) . ' ' . $fecha_check;
			$html .= '</p>';
		}

		$html .= '<p>';
		if ( version_compare( CL_WP_INFO_REC_PHP, PHP_VERSION, '<=' ) ) {
			$html .= '<span class="cl-ok">' . esc_html__( 'Excellent: Your server PHP version is the same or greater than WordPress recomended.', 'cl-wp-info' ) . '</span>';
		} elseif ( version_compare( CL_WP_INFO_MIN_PHP, PHP_VERSION, '<=' ) ) {
			$html .= '<span class="cl-warning">' . esc_html__( 'Your server PHP version is the same or greater than WordPress minimum, but lower that recomended. Please update your server PHP Version.', 'cl-wp-info' ) . '</span>';
		} else {
			$html .= '<span class="cl-error">' . esc_html__( 'BAD: Your server PHP version is lower than minimum required. Update your server PHP Version.', 'cl-wp-info' ) . '</span>';
		}
		$html .= '</p>';

		$html .= '<p>';
		if ( version_compare( CL_WP_INFO_MIN_DB, $this->db_version, '<=' ) ) {
			$html .= '<span class="cl-ok">' . esc_html__( 'Your server database version is the same or greater than WordPress recomended.', 'cl-wp-info' ) . '</span>';
		} else {
			$html .= '<span class="cl-error">' . esc_html__( 'BAD: Your server database version is lower than minimum required. Update your server database Version.', 'cl-wp-info' ) . '</span>';
		}
		$html .= '</p>';

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	} // Final de cl_wp_info_general.

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
		foreach ( $php_extensions as $valor ) {
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

		if ( ! empty( $php_ini['date.default_latitude'] ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Date/Time latitude:', 'cl-wp-info' ) . '</th>';
			$html .= '<td>';
			$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['date.default_latitude']['global_value'] . '</div>';
			$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['date.default_latitude']['local_value'] . '</div>';
			$html .= '</tr>';
		}

		if ( ! empty( $php_ini['date.default_longitude'] ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Date/Time longitude:', 'cl-wp-info' ) . '</th>';
			$html .= '<td>';
			$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['date.default_longitude']['global_value'] . '</div>';
			$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['date.default_longitude']['local_value'] . '</div>';
			$html .= '</tr>';
		}

		if ( ! empty( $php_ini['default_charset'] ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'Default charset:', 'cl-wp-info' ) . '</th>';
			$html .= '<td>';
			$html .= '<div><strong>' . esc_html__( 'PHP Global:', 'cl-wp-info' ) . '</strong> ' . $php_ini['default_charset']['global_value'] . '</div>';
			$html .= '<div><strong>' . esc_html__( 'App Local:', 'cl-wp-info' ) . '</strong> ' . $php_ini['default_charset']['local_value'] . '</div>';
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

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Database Server:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $this->db_version . '</td>';
		$html .= '</tr>';

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

		if ( defined( 'DB_USER' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress Database Username:', 'cl-wp-info' ) . '</th>';
			$html .= '<td><strong><em>' . esc_html__( 'Hidden for security purposes', 'cl-wp-info' ) . '.</em></strong><br />' . esc_html__( 'You can view Database Username in constant DB_USER defined in your wp-config.php in WordPress root folder', 'cl-wp-info' ) . '</td>';
			$html .= '</tr>';
		}

		if ( defined( 'DB_PASSWORD' ) ) {
			$html .= '<tr>';
			$html .= '<th>' . esc_html__( 'WordPress Database Password:', 'cl-wp-info' ) . '</th>';
			$html .= '<td><strong><em>' . esc_html__( 'Hidden for security purposes', 'cl-wp-info' ) . '.</em></strong><br />' . esc_html__( 'You can view Database Password in constant DB_PASSWORD defined in your wp-config.php in WordPress root folder', 'cl-wp-info' ) . '</td>';
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
		$html .= '<td>' . $this->wp_version . '</td>';
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

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress locale:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>' . $this->wp_locale . '</td>';
		$html .= '</tr>';

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

		// Posts section.
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Posts:', 'cl-wp-info' ) . '</th>';
		$html .= '<td><ul>';
		/* translators: number of posts published. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d published post', '%d published posts', $this->n_posts->publish, 'cl-wp-info' ) ), $this->n_posts->publish ) . '</li>';
		/* translators: number of posts future. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d post to publish in the future', '%d posts to publish in the future', $this->n_posts->future, 'cl-wp-info' ) ), $this->n_posts->future ) . '</li>';
		/* translators: number of posts pending. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d post pending review', '%d posts pending review', $this->n_posts->pending, 'cl-wp-info' ) ), $this->n_posts->pending ) . '</li>';
		/* translators: number of posts in draft. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d post in draft status', '%d posts in draft status', $this->n_posts->draft, 'cl-wp-info' ) ), $this->n_posts->draft ) . '</li>';
		/* translators: number of posts in auto-draft. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d newly created post with no content', '%d newly created posts with no content', $this->n_posts->{'auto-draft'}, 'cl-wp-info' ) ), $this->n_posts->{'auto-draft'} ) . '</li>';
		/* translators: number of private posts. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d post not visible to users who are not logged in', '%d posts not visible to users who are not logged in', $this->n_posts->private, 'cl-wp-info' ) ), $this->n_posts->private ) . '</li>';
		/* translators: number of posts in revision. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d revision post', '%d revision posts', $this->n_posts->inherit, 'cl-wp-info' ) ), $this->n_posts->inherit ) . '</li>';
		/* translators: number of posts in trash. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d post in trashbin', '%d posts in trashbin', $this->n_posts->trash, 'cl-wp-info' ) ), $this->n_posts->trash ) . '</li>';
		$html .= '</ul></td>';
		$html .= '</tr>';

		// Pages section.
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Pages:', 'cl-wp-info' ) . '</th>';
		$html .= '<td><ul>';
		/* translators: number of pages published. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d published page', '%d published pages', $this->n_pages->publish, 'cl-wp-info' ) ), $this->n_pages->publish ) . '</li>';
		/* translators: number of pages future. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d page to publish in the future', '%d pages to publish in the future', $this->n_pages->future, 'cl-wp-info' ) ), $this->n_pages->future ) . '</li>';
		/* translators: number of pages pending. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d page pending review', '%d pages pending review', $this->n_pages->pending, 'cl-wp-info' ) ), $this->n_pages->pending ) . '</li>';
		/* translators: number of pages in draft. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d page in draft status', '%d pages in draft status', $this->n_pages->draft, 'cl-wp-info' ) ), $this->n_pages->draft ) . '</li>';
		/* translators: number of pages in auto-draft. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d newly created page with no content', '%d newly created pages with no content', $this->n_pages->{'auto-draft'}, 'cl-wp-info' ) ), $this->n_pages->{'auto-draft'} ) . '</li>';
		/* translators: number of private pages. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d page not visible to users who are not logged in', '%d pages not visible to users who are not logged in', $this->n_pages->private, 'cl-wp-info' ) ), $this->n_pages->private ) . '</li>';
		/* translators: number of pages in revision. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d revision page', '%d revision pages', $this->n_pages->inherit, 'cl-wp-info' ) ), $this->n_pages->inherit ) . '</li>';
		/* translators: number of pages in trash. */
		$html .= '<li>' . sprintf( esc_html( _n( '%d page in trashbin', '%d pages in trashbin', $this->n_pages->trash, 'cl-wp-info' ) ), $this->n_pages->trash ) . '</li>';
		$html .= '</ul></td>';
		$html .= '</tr>';

		// Comments section.
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Comments:', 'cl-wp-info' ) . '</th>';
		/* translators: number of comments. */
		$html .= '<td>' . sprintf( esc_html( _n( '%d comment', '%d comments', $this->n_comments, 'cl-wp-info' ) ), $this->n_comments ) . '</td>';
		$html .= '</tr>';

		// Media section.
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Media:', 'cl-wp-info' ) . '</th>';
		$html .= '<td><ul>';

		foreach ( $this->n_media as $media_type => $media_num ) {
			if ( ! empty( $media_num ) && 'trash' !== $media_type ) {
				$html .= '<li>' . esc_html__( 'MIME Type', 'cl-wp-info' ) . ' <em>(' . $media_type . ')</em>: ' . $media_num . '</li>';
			}
		}
		$html .= '<li>' . esc_html__( 'Trash:', 'cl-wp-info' ) . ' ' . $this->n_media->trash . '</li>';
		$html .= '</ul></td>';
		$html .= '</tr>';

		// Users section.
		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'WordPress Users:', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		/* translators: number of users. */
		$html .= '<strong>' . sprintf( esc_html( _n( '%d user', '%d users', $this->n_users['total_users'], 'cl-wp-info' ) ), $this->n_users['total_users'] ) . '</strong><br />';

		$html .= '<ul>';
		foreach ( $this->n_users['avail_roles'] as $user_rol => $user_num ) {
			if ( ! empty( $user_num ) ) {
				$html .= '<li>' . $user_rol . ': ' . $user_num . '</li>';
			}
		}
		$html .= '</ul>';
		$html .= '</td>';
		$html .= '</tr>';

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
				$html .= $valor_tema->get( 'Author' );
			} else {
				$html .= '<a href="' . $valor_tema->get( 'AuthorURI' ) . '" target="_blank" rel="noopener noreferrer">' . $valor_tema->get( 'Author' ) . '</a>';
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

	/**
	 * Devuelve los javascript y CSS de WordPress.
	 *
	 * @since     1.2.0
	 *
	 * @param boolean $echo Escribir la salida o devolverla.
	 */
	public function cl_wp_js_cs( $echo = true ) {
		global $wp_scripts, $wp_styles;

		$html  = '';

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'Javascript files (in this page):', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<ol>';
		foreach ( $wp_scripts->queue as $script ) {
			$html .= '<li>' . $script . ' => ' . $wp_scripts->registered[ $script ]->src . '</li>';
		}
		$html .= '</ol>';
		$html .= '</td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<th>' . esc_html__( 'CSS files (in this page):', 'cl-wp-info' ) . '</th>';
		$html .= '<td>';
		$html .= '<ol>';
		foreach ( $wp_styles->queue as $style ) {
			$html .= '<li>' . $style . ' => ' . $wp_styles->registered[ $style ]->src . '</li>';
		}
		$html .= '</ol>';
		$html .= '</td>';
		$html .= '</tr>';

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	} // Final de cl_wp_js_cs.
}
