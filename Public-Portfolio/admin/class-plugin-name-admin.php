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
	public static function JWT_Credentials()
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


		// Generated @ codebeautify.org
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://prod-api.154310543964.hellopublic.com/static/anonymoususer/credentials.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$auth = json_decode($result);
		return $auth->jwt;
	}

	public static function curl_headers(){
		return array(
			"Accept: application/json",
			"Authorization: Bearer " . Public_Portfolio_Admin::JWT_Credentials(),
		);
	}

	public static function user_Credentials()
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

		$username = 'ryntab';

		$url = "https://prod-api.154310543964.hellopublic.com/communityservice/users?username=" . $username;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = Public_Portfolio_Admin::curl_headers();

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$ID = curl_exec($curl);
		curl_close($curl);

		return json_decode($ID);
	}

	public static function get_User_Data()
	{
		$ID = Public_Portfolio_Admin::user_Credentials()->publicId;

		$url = "https://prod-api.154310543964.hellopublic.com/communityservice/users/".$ID;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = Public_Portfolio_Admin::curl_headers();
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$userData = curl_exec($curl);
		curl_close($curl);
		return json_decode($userData);
	}

	public static function cron_Get_User_Data()
	{
		$userData = Public_Portfolio_Admin::get_User_Data();
		update_option( 'public_user_data', $userData, false);
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

add_action('public_Data_Fetcher', [ __NAMESPACE__ . '\Public_Portfolio_Admin','cron_Get_User_Data' ]);

if (!wp_next_scheduled('public_Data_Fetcher')) {
    wp_schedule_event(time(), 'every_three_minutes', 'public_Data_Fetcher');
}

// if( wp_next_scheduled( 'public_Data_Fetcher' ) ){
// wp_clear_scheduled_hook( 'public_Data_Fetcher' );
// }

add_action('my_unique_plugin_event_hook', array($this,'hook'));