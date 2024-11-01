<?php
/**
 * Form template
 *
 * @package SocialShareForDevs
 */

namespace SocialShareForDevs;

?>

<div class="wrap wcssb-wrap">
	<h1 class="wcssb-wrap__title"><?php echo esc_html_e( 'Social Share for Devs Settings', 'social-share-for-devs' ); ?></h1>
	<form class="wcssb-wrap__form" action="options.php" method="POST">
		<h4><?php echo esc_html_e( 'Select the social buttons you want to display on the pages', 'social-share-for-devs' ); ?></h4>
		<?php
		settings_errors();
		settings_fields( 'ssfd_plugin' );
		do_settings_sections( 'ssfd-settings-page' );
		submit_button();
		?>
	</form>
	<div>
		<div>
			<p><b><?php echo esc_html_e( 'Preview:', 'social-share-for-devs' ); ?></b></p>
			<?php
			$socialsharefordevs_shortcode = new Shortcode();

			echo wp_kses(
				$socialsharefordevs_shortcode->shortcode_social_share(
					array(
						'url' => home_url(),
					)
				),
				array(
					'svg'   => array(
						'class'           => true,
						'aria-hidden'     => true,
						'aria-labelledby' => true,
						'role'            => true,
						'xmlns'           => true,
						'width'           => true,
						'height'          => true,
						'viewbox'         => true, // <= Must be lower case!
					),
					'g'     => array( 'fill' => true ),
					'title' => array( 'title' => true ),
					'path'  => array(
						'd'    => true,
						'fill' => true,
					),
					'a'     => array(
						'class'      => true,
						'href'       => true,
						'aria-label' => true,
						'target'     => true,
						'onclick'    => true,
					),
					'div'   => array(
						'class' => true,
					),
				)
			)

			?>
		</div>
		<p><b><?php esc_html_e( 'Usage:', 'social-share-for-devs' ); ?></b></p>
		<p><?php echo wp_kses_post( __( 'Add the shortcode <b>[wc-ssb]</b> to the page where you want to display the buttons.', 'social-share-for-devs' ) ); ?></p>
		<p><?php echo wp_kses_post( __( 'You can also add the shortcode to a post or a page using the <b>Add Shortcode</b> button in the editor.', 'social-share-for-devs' ) ); ?></p>
		<p><?php echo wp_kses_post( __( 'This shortcode support some attributes <b>[wc-ssb url="" title="" thumbnail=""]</b>.', 'social-share-for-devs' ) ); ?></p>
		<p><?php echo wp_kses_post( __( 'If you don\'t pass any attributes, the shortcode gets them from the current page or post.', 'social-share-for-devs' ) ); ?></p>
	</div>
</div>
