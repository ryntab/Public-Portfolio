<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Public_Portfolio
 * @subpackage Public_Portfolio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Public_Portfolio
 * @subpackage Public_Portfolio/public
 * @author     Your Name <email@example.com>
 */
class Public_Portfolio_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode('public_portfolio_watchlist', array($this, 'register_public_portfolio_watchlist'));
		add_shortcode('public_portfolio_bio', array($this, 'register_public_portfolio_bio'));
		add_shortcode('public_stock_embed', array($this, 'register_public_stock_embed'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/plugin-name-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		
		wp_enqueue_script('popper', plugin_dir_url(__FILE__) . 'js/popper.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script('tippy', plugin_dir_url(__FILE__) . 'js/tippy.min.js', array('popper'), $this->version, false);
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/public.js', array('jquery', 'popper'), $this->version, false);
		wp_add_inline_script($this->plugin_name, 'const auth = "'.Public_Portfolio_Admin::JWT_Credentials().'";');
	}

	public function register_public_portfolio_watchlist($atts)
	{
		$watchedStocksData = get_option('public_user_watchlist_data');

		if (isset($atts['ticker'])) $onlyTickers = explode(',', str_replace(' ', '', $atts['ticker']));
		
		if (isset($atts['hideticker'])) $hideTickers = explode(',', str_replace(' ', '', $atts['hideticker']));

		echo '<div class="css-firzh2">';
		foreach ($watchedStocksData->quotes as $ticker) {

			if (isset($onlyTickers)) {
				if (!in_array($ticker->symbol, $onlyTickers)) {
					continue;
				}
			}

			if (isset($hideTickers)) {
				if (in_array($ticker->symbol, $hideTickers)){
					continue;
				}
			}

			

			

			$percentage = number_format((float)$ticker->gainPercentage, 2, '.', '');
			$color = ($percentage < 0) ? $color = 'red' : $color = 'green';
			echo '<div role="listitem" tabindex="0" class="css-mub7"><div><article class="css-x8v0h7"><a aria-label="$' . $ticker->symbol . '"><div data-logo="true" class="css-o6on0v"><img alt="' . $ticker->symbol . ' logo" loading="lazy" src="https://universal.hellopublic.com/companyLogos/' . $ticker->symbol . '@2x.png?v=fa60680a-a44c-4a20-9f07-1aa83dc487d6" class="css-3j1uxc"></div></a><div class="css-jifwjk"><a href="/stocks/' . $ticker->symbol . '"><span class="css-uahqw4"> $' . $ticker->symbol . '</span></a></div><button class="css-gpmf5p" style="color: var(--color-' . $color . '); background-color: var(--color-light-' . $color . ');"><span>' . $percentage . '%</span></button></article></div></div>';
		}
		echo '</section></div>';
	}

	public function register_public_portfolio_bio()
	{
		$userData = get_option('public_user_data');
		$html = '<div class="public-card">';
		$html .= '<div class="public-identity-top">';
		$html .= '<div class="public-identity">';
		$html .= '<a href="#" class="public-following">';
		$html .= $userData->followersCount . ' Followers • ' . $userData->followingCount . ' Following</a>';
		$html .= '<div class="public-name">' . $userData->displayName . '</div>';
		$html .= '<div class="public-username"><span class="public-userName">@' . $userData->username . '</span></div></div>';
		$html .= '<div class="public-profile-image-container"><a aria-label="ryntab" href="/ryntab">';
		$html .= '<img loading="lazy" alt="Ryan Taber avatar" src="' . $userData->profilePictureURL . '"></a></div></div>';
		$html .= '<div class="public-bio"><span>' . $userData->bio . '</span></div>';
		$html .= '<div class="public-link"><span class="css-1b27v1c">' . $userData->websiteLink . '</a></div></div>';
		echo $html;
	}


	public function register_public_stock_embed($atts)
	{
		$ticker = $atts['ticker'];
		if (!$ticker) return;
		return '<iframe width="100%" height="312" src="https://public.com/stocks/' . $ticker . '/embed" frameborder="0" allow="encrypted-media" allowfullscreen allowtransparency></iframe>';
	}

	public function filter_stock_mentions($content)
	{
		if ((is_single() || is_page())) {
			preg_replace('/([\$])\w+/m', 'wdawdwd', $content);


			return preg_replace_callback_array([
				// 1. Removes WordPress injected <p> tags surrounding images in post content.
				'/([\$])\w+/m' => function (&$matches) {
						return '<span class="public-ticker" data-symbol="'.str_replace('$', '', $matches[0]).'"><span class="ticker"><strong>' . $matches[0] . '</strong></span></span>';
				},
				// 2. Adds custom data-attribute to <p> tags providing a paragraph id number.
				// '|<p>|' => function (&$matches) {
				// 	static $i = 1;
				// 	return sprintf('<p data-p-id="%d">', $i++);
				// },
			], $content);


			// preg_match_all('/([\$])\w+/m', $content, $matches);
			// foreach($matches[0] as $ticker){

			// }
			return $content;
		}
		return $content;
	}
}