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
class Public_Portfolio_Public {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'public_portfolio', array($this, 'register_public_portfolio_shortcode') );
		add_shortcode( 'public_portfolio_bio', array($this, 'register_public_portfolio_bio') );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), $this->version, false );
	}

	public function register_public_portfolio_shortcode(){
		$userData = get_option('public_user_data');
		//echo '<div class="css-firzh2"><div role="radiogroup" class="css-14vo4ai"><label aria-checked="true" class="css-q5qaxf"><input type="radio" checked="" name="filter" value="portfolio"><span>Portfolio</span></label><label aria-checked="false" class="css-q5qaxf"><input type="radio" name="filter" value="longterm"><span>Long-term</span></label><label aria-checked="false" class="css-q5qaxf"><input type="radio" name="filter" value="watchlist"><span>Watchlist</span></label></div><section role="list" class="css-1dx5e52">';
		
		//print_r($userData->positions->positionEntries);
		foreach($userData->positions->positionEntries as $key){
			//echo '<div role="listitem" tabindex="0" class="css-mub7"><div><article class="css-x8v0h7"><a aria-label="'.$key->name.'" href="/stocks/ACB"><div data-logo="true" class="css-o6on0v"><img alt="ACB logo" loading="lazy" src="https://universal.hellopublic.com/companyLogos/ACB@2x.png?v=fa60680a-a44c-4a20-9f07-1aa83dc487d6" class="css-3j1uxc"></div></a><div class="css-jifwjk"><a href="/stocks/ACB"><span class="css-uahqw4">'.$key->symbol.'</span></a></div><button class="css-gpmf5p"><span>+3.15%</span></button></article></div></div>';
		}
		//echo '</section><button class="css-1aqck32"><span>Show all</span></button></div>';


	}

	public function register_public_portfolio_bio(){
		$userData = get_option('public_user_data');
		$html = '<div class="public-card">';
		$html .= '<div class="public-identity-top">';
		$html .= '<div class="public-identity">';
		$html .= '<a href="#" class="public-following">';
		$html .= $userData->followersCount.' Followers • '.$userData->followingCount.' Following</a>';
		$html .= '<div class="public-name">'.$userData->displayName.'</div>';
		$html .= '<div class="public-username"><span class="public-userName">@'.$userData->username.'</span></div></div>';
		$html .= '<div class="public-profile-image-container"><a aria-label="ryntab" href="/ryntab">';
		$html .= '<img loading="lazy" alt="Ryan Taber avatar" src="'.$userData->profilePictureURL.'"></a></div></div>';
		$html .= '<div class="public-bio"><span>'.$userData->bio.'</span></div>';
		$html .= '<div class="public-link"><span class="css-1b27v1c">'.$userData->websiteLink.'</a></div></div>';
		echo $html;
	}

}
