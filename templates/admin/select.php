<?php
/**
 * Select template
 *
 * @package SocialShareForDevs
 */

?>

<select name="ssfd_type">
			<option value="default" <?php selected( esc_attr( get_option( 'ssfd_type' ) ), 'default' ); ?>>Default</option>
			<option value="rounded" <?php selected( esc_attr( get_option( 'ssfd_type' ) ), 'rounded' ); ?>>Rounded</option>
			<option value="icon" <?php selected( esc_attr( get_option( 'ssfd_type' ) ), 'icon' ); ?>>Square Icon</option>
			<option value="circle" <?php selected( esc_attr( get_option( 'ssfd_type' ) ), 'circle' ); ?>>Circle Icon</option>
			<option value="custom" <?php selected( esc_attr( get_option( 'ssfd_type' ) ), 'custom' ); ?>>Custom (No styles)</option>
</select>
