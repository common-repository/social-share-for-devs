<?php
/**
 * Social Share For Devs
 *
 * @package           SocialShareForDevs
 * @author            White Canvas
 * @copyright         2022 White Canvas
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Social Share for Devs
 * Plugin URI:        https://wcanvas.com/plugins
 * Description:       A simple, lightweight plugin to add social share buttons to your website.
 * Version:           1.0.9
 * Requires at least: 4.7
 * Requires PHP:      7.2
 * Author:            White Canvas
 * Author URI:        https://wcanvas.com
 * Text Domain:       social-share-for-devs
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
Basics Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Basics Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Basic Plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Composer autoloader
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Load Settings and Shortcode classes
 */
new SocialShareForDevs\Settings();
new SocialShareForDevs\Shortcode();

/**
 * Development Updates
 */
new SocialShareForDevs\Updates( __FILE__ );
