<?php

/**
 * @link              https://jamessteinbach.com
 * @since             1.0.0
 * @package           API_Bot
 *
 * @wordpress-plugin
 * Plugin Name:       API Bot 🤖
 * Plugin URI:        https://jamessteinbach.com/api-bot/
 * Description:       This plugin provides a framework for accessing 3rd party APIs via PHP in server-side PHP templates and via Ajax through front-end JS.
 * Version:           1.0.0
 * Author:            James Steinbach
 * Author URI:        https://jamessteinbach.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       api-bot
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


class API_Bot_Init {
  /**
   * Require all the classes needed to run the API_Bot
   */
  function __construct()
  {
    // Reusable cURL class
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-curl.php';

    // @TODO Reusable custom DB table class
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-table.php';

    // Generic API_Bot creates internal APIs for accessing other APIs via PHP & JS
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-api-bot.php';

    // Class for calling 3rd party (external) APIs
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-external-api-bot.php';
  }
}

$api_bot_init = new API_Bot_Init();
