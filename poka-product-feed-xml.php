<?php

/**
 * @link              https://pokamedia.com
 * @since             1.0.0
 * @package           Poka_PFXML
 * @author Thien Tran <thientran2359@gmail.com>
 * 
 * @wordpress-plugin
 * Plugin Name:       Poka Product Feed XML
 * Plugin URI:        https://pokamedia.com/poka-product-feed-xml
 * Description:       Product Feed for Project TechLandAudio
 * Version:           1.0.0
 * Author:            PokaMedia
 * Author URI:        https://pokamedia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       poka-product-feed-xml
 * Domain Path:       /languages
 */
// Block direct access to file
defined('ABSPATH') or die('Not Authorized!');

// Plugin Defines
define("POKA_PFX_FILE", __FILE__);
define("POKA_PFX_DIRECTORY", dirname(__FILE__));
define("POKA_PFX_DIRECTORY_BASENAME", plugin_basename(POKA_PFX_FILE));
define("POKA_PFX_TEXT_DOMAIN", "poka-product-feed-xml");
define("POKA_PFX_DIRECTORY_PATH", plugin_dir_path(POKA_PFX_FILE));
define("POKA_PFX_DIRECTORY_URL", plugins_url(null, POKA_PFX_FILE));

// Require the main class file
require_once( POKA_PFX_DIRECTORY . '/include/main-class.php' );