<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Public_Portfolio
 * @subpackage Public_Portfolio/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Public_Portfolio
 * @subpackage Public_Portfolio/includes
 * @author     Your Name <email@example.com>
 */
class Public_Portfolio_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$tableName = 'public_stored_tickers';
		$sql = "CREATE TABLE `{$tableName}` (
			ticker_symbol varchar(5) NOT NULL,
			ticker_data LONGTEXT NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (ticker_symbol)
			) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	}

}
