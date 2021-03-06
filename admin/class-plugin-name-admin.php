<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Public_Portfolio
 * @subpackage Public_Portfolio/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Public_Portfolio
 * @subpackage Public_Portfolio/admin
 * @author     Your Name <email@example.com>
 */
class Public_Portfolio_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Public_Portfolio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Public_Portfolio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/plugin-name-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Public_Portfolio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Public_Portfolio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/plugin-name-admin.js', array('jquery'), $this->version, false);
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */

	public static function curl_this($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = Public_Portfolio_Admin::curl_headers();
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$returnedData = json_decode(curl_exec($curl));
		curl_close($curl);
		return $returnedData;
	}

	public static function curl_headers()
	{
		return array(
			"Accept: application/json",
			"Authorization: Bearer " . Public_Portfolio_Admin::JWT_Credentials(),
		);
	}

	public static function JWT_Credentials()
	{
		// Generated @ codebeautify.org
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, PUBLIC_GRAPH_USERS . 'static/anonymoususer/credentials.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$auth = json_decode($result);
		return $auth->jwt;
	}

	public static function user_Credentials()
	{
		$username = 'ryntab';

		$url = PUBLIC_GRAPH_USERS . "communityservice/users?username=" . $username;

		return Public_Portfolio_Admin::curl_this($url);
	}

	public static function get_User_Data()
	{
		$ID = Public_Portfolio_Admin::user_Credentials()->publicId;

		$url = PUBLIC_GRAPH_USERS . "communityservice/users/" . $ID;

		return Public_Portfolio_Admin::curl_this($url);
	}

	public static function save_Watched_Entities_Data()
	{

		$watchlistEntities = implode(",", get_option('public_user_watchlist'));

		$url = PUBLIC_GRAPH_API . "quotes?symbols=" . $watchlistEntities;

		update_option('public_user_watchlist_data', Public_Portfolio_Admin::curl_this($url), false);
	}

	public static function save_Opened_Positions_Data()
	{
		$watchlistEntities = implode(",", get_option('public_user_positions'));

		$url = PUBLIC_GRAPH_API . "quotes?symbols=" . $watchlistEntities;

		update_option('public_user_positions_data', Public_Portfolio_Admin::curl_this($url), false);
	}

	public static function cron_Save_User_Data()
	{
		$userData = Public_Portfolio_Admin::get_User_Data();

		$watchlist = array();
		$postions = array();

		foreach ($userData->positions->positionEntries as $ticker) {
			array_push($postions, $ticker->symbol);
		}

		foreach ($userData->watchlist->watchedEntities as $ticker) {
			array_push($watchlist, $ticker->symbol);
		}

		Public_Portfolio_Admin::save_Watched_Entities_Data();
		Public_Portfolio_Admin::save_Opened_Positions_Data();

		update_option('public_user_positions', $postions, false);
		update_option('public_user_watchlist', $watchlist, false);
		update_option('public_user_data', $userData, false);
	}

	public static function set_Individual_Stock($symbol, $exists)
	{
		global $wpdb;

		$interval = 'DAY';
		$table = 'public_stored_tickers';
		$column = 'ticker_symbol';

		$url = PUBLIC_GRAPH_API . "graphs/stock/" . $symbol . "/" . $interval;

		$response = Public_Portfolio_Admin::curl_this($url);

		$new = array(
			'ticker_symbol' => $symbol,
			'ticker_data' => json_encode($response),
			'updated_at' => date('Y-m-d H:i:s')
		);

		if ($exists) {
			$wpdb->replace('public_stored_tickers', $new);
		} else if (!$exists) {
			$wpdb->insert('public_stored_tickers', $new);
		}

		$data = $wpdb->get_results("SELECT * FROM $table WHERE $column = '$symbol'");

		return $data;
	}

	public static function get_Individual_Stock($data)
	{
		global $wpdb;

		$symbol = $data['symbol'];

		$table = 'public_stored_tickers';
		$column = 'ticker_symbol';

		$readTicker = $wpdb->get_results("SELECT * FROM $table WHERE $column = '$symbol'");

		//If record does not exist, lets create it!
		if (empty($readTicker)) {
			$individualStock = Public_Portfolio_Admin::set_Individual_Stock($data['symbol'], $exists = false);
		};

		//If record is stale, lets update it! Or if its not lets just fetch from the database.
		if (!empty($readTicker)) {
			if (strtotime(date('Y-m-d H:i:s')) - strtotime($readTicker[0]->updated_at) > 60) {
				$individualStock = Public_Portfolio_Admin::set_Individual_Stock($data['symbol'], $exists = true);
			} else {
				$individualStock = $readTicker;
			}
		}

		$response['last_Updated'] = $individualStock[0]->updated_at;
		$response['ticker_symbol'] = $individualStock[0]->ticker_symbol;
		$response['ticker_data'] = json_decode($individualStock[0]->ticker_data, true);
		wp_send_json($response);
	}


	public static function individual_stock_endpoint()
	{
		register_rest_route('public', '/stock/(?P<symbol>[a-zA-Z0-9-]+)/(?P<interval>[a-zA-Z0-9-]+)', array(
			'methods' => 'GET',
			'callback' => ["Public_Portfolio_Admin" , 'get_Individual_Stock'],
		));
	}
}

add_filter('cron_schedules', 'public_Data_Fetcher');
function public_Data_Fetcher($schedules)
{
	$schedules['every_three_minutes'] = array(
		'interval' => 60,
		'display' => __('Every 60 Seconds', 'textdomain')
	);
	return $schedules;
}

add_action('public_Data_Fetcher', [__NAMESPACE__ . '\Public_Portfolio_Admin', 'cron_Save_User_Data']);

if (!wp_next_scheduled('public_Data_Fetcher')) {
	wp_schedule_event(time(), 'every_three_minutes', 'public_Data_Fetcher');
}
