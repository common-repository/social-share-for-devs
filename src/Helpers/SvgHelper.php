<?php
/**
 * This class allows you to get svg's files
 *
 * @package SocialShareForDevs
 */

namespace SocialShareForDevs\Helpers;

/**
 * Class SvgHelper
 */
class SvgHelper {

	/**
	 * Function to get svg
	 *
	 * @param string $path string for svg.
	 */
	public static function get_svg( string $path ): string {
		return file_get_contents( plugin_dir_path( __FILE__ ) . '../../' . $path );
	}
}
