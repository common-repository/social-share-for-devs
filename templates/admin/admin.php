<?php
/**
 * Admin template
 *
 * @package SocialShareForDevs
 */

?>

<input type="checkbox" name="<?php echo esc_attr( $args['theName'] ); ?>" value="1" <?php checked( esc_attr( get_option( $args['theName'] ) ), '1' ); ?>>

