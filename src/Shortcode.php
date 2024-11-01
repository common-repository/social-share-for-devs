<?php
/**
 * Shortcode
 *
 * @package SocialShareForDevs
 */

namespace SocialShareForDevs;

use SocialShareForDevs\Helpers\SvgHelper;

/**
 * Class Shortcode
 */
class Shortcode {

	/**
	 * Variable for settings
	 *
	 * @var settings
	 */
	private $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'ssfd_custom_shortcode' ) );

		// Load settings from DB.
		$this->settings = array(
			'facebook'  => esc_attr( get_option( 'ssfd_facebook' ) ),
			'twitter'   => esc_attr( get_option( 'ssfd_twitter' ) ),
			'whatsapp'  => esc_attr( get_option( 'ssfd_whatsapp' ) ),
			'pinterest' => esc_attr( get_option( 'ssfd_pinterest' ) ),
			'email'     => esc_attr( get_option( 'ssfd_email' ) ),
			'linkedin'  => esc_attr( get_option( 'ssfd_linkedin' ) ),
			'type'      => esc_attr( get_option( 'ssfd_type' ) ),
			'class'     => esc_attr( get_option( 'ssfd_class' ) ),
		);
	}

	/**
	 * Register Shortcode
	 */
	public function ssfd_custom_shortcode() {
		add_shortcode( 'wc-ssb', array( $this, 'shortcode_social_share' ) );
	}

	/**
	 * Custom Method to generate class attribute
	 *
	 * @param string $type type of button.
	 * @return string
	 */
	public function get_class_of_type( $type ) {
		$class = '';
		switch ( $type ) {
			case 'default':
				$class = 'wc-social-share-buttons-link wc-social-share-buttons-';
				break;
			case 'rounded':
				$class = 'wc-social-share-rounded-link wc-social-share-buttons-';
				break;
			case 'icon':
				$class = 'wc-social-share-icon-link wc-social-share-buttons-';
				break;
			case 'circle':
				$class = 'wc-social-share-circle-link wc-social-share-buttons-';
				break;
			case 'custom':
				$class = $this->settings['class'] . '-';
				break;
			default:
				$class = 'wc-social-share-buttons-link wc-social-share-buttons-';
				break;
		}
		return $class;
	}

	/**
	 * Custom method to generate label or icon
	 *
	 * @param string $type type of button.
	 * @param string $label label for button.
	 * @return string
	 */
	public function get_icon_or_label( $type, $label ) {
		$html   = '';
		$_label = strtolower( $label );

		switch ( $type ) {
			case 'default':
				$html = SvgHelper::get_svg( '/assets/img/' . $_label . '.svg' ) . $label;
				break;
			case 'rounded':
				$html = SvgHelper::get_svg( '/assets/img/' . $_label . '.svg' ) . $label;
				break;
			case 'icon':
				$html = SvgHelper::get_svg( '/assets/img/' . $_label . '.svg' );
				break;
			case 'circle':
				$html = SvgHelper::get_svg( '/assets/img/' . $_label . '.svg' );
				break;
			default:
				$html = SvgHelper::get_svg( '/assets/img/' . $_label . '.svg' ) . $label;
				break;
		}

		return $html;
	}

	/**
	 * FrontEnd Assets
	 */
	private function add_styles() {
			wp_enqueue_style(
				'ssfd-style',
				plugins_url( '../assets/build/wc-social-share.min.css', __FILE__ ),
				null,
				filemtime( plugin_dir_path( __FILE__ ) . '../assets/build/wc-social-share.min.css' )
			);
	}

	/**
	 * Shortcode HTML
	 *
	 * @param array  $atts array of attributes.
	 * @param string $content string of content.
	 */
	public function shortcode_social_share( $atts, $content = '' ) {

		$this->add_styles();

		$attributes = shortcode_atts(
			array(
				'title'     => false,
				'url'       => false,
				'thumbnail' => false,
			),
			$atts
		);

		if ( false !== $attributes['url'] ) {
			$ssfd_url           = $attributes['url'];
			$ssfd_title         = $attributes['title'];
			$ssfd_thumbnail_img = $attributes['thumbnail'];
		} else {
			global $post;
			$ssfd_url           = rawurlencode( get_permalink() );
			$ssfd_title         = htmlspecialchars( rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' );
			$ssfd_thumbnail     = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$ssfd_thumbnail_img = $ssfd_thumbnail[0] ?? '';
		}

		/**
		 * Set buttons' for styling
		 */
		$ssfd_class = $this->settings['class'];

		/**
		 * Set buttons' type
		 */
		$ssfd_type = $this->get_class_of_type( $this->settings['type'] );

		/**
		 * Construct sharing URL without using any script
		 */
		$twitter_url   = 'https://twitter.com/intent/tweet?text=' . $ssfd_title . '&amp;url=' . $ssfd_url . '&amp;via=' . get_bloginfo( 'name' );
		$facebook_url  = 'https://www.facebook.com/sharer/sharer.php?u=' . $ssfd_url;
		$linkedin_url  = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $ssfd_url;
		$whatsapp_url  = 'https://api.whatsapp.com/send?text=' . $ssfd_title . ' ' . $ssfd_url;
		$email_url     = 'mailto:?subject=' . $ssfd_title . '&amp;body=Check out this site: ' . $ssfd_url;
		$pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $ssfd_url . '&amp;media=' . $ssfd_thumbnail_img . '&amp;description=' . $ssfd_title;

		/**
		 *  Add sharing button at the end of page/page content
		 */
		$content .= '<div class="wc-social-share-buttons-social">';

		if ( '1' === $this->settings['facebook'] ) {
			$content .= '<a class="' . $ssfd_type . 'facebook ' . $ssfd_class . '" href="' . $facebook_url . '" target="popup" onclick="window.open(\'' . $facebook_url . '\',\'popup\',\'width=600,height=600,scrollbars=no,resizable=no\'); return false;">' . $this->get_icon_or_label( $this->settings['type'], 'Facebook' ) . '</a>';
		}

		if ( '1' === $this->settings['twitter'] ) {
			$content .= '<a class="' . $ssfd_type . 'twitter ' . $ssfd_class . '" href="' . $twitter_url . '" target="popup" onclick="window.open(\'' . $twitter_url . '\',\'popup\',\'width=600,height=600,scrollbars=no,resizable=no\'); return false;">' . $this->get_icon_or_label( $this->settings['type'], 'X' ) . '</a>';
		}

		if ( '1' === $this->settings['linkedin'] ) {
			$content .= '<a class="' . $ssfd_type . 'linkedin ' . $ssfd_class . '" href="' . $linkedin_url . '" target="popup" onclick="window.open(\'' . $linkedin_url . '\',\'popup\',\'width=600,height=600,scrollbars=no,resizable=no\'); return false;">' . $this->get_icon_or_label( $this->settings['type'], 'LinkedIn' ) . '</a>';
		}

		if ( '1' === $this->settings['whatsapp'] ) {
			$content .= '<a class="' . $ssfd_type . 'whatsapp ' . $ssfd_class . '" href="' . $whatsapp_url . '" target="popup" onclick="window.open(\'' . $whatsapp_url . '\',\'popup\',\'width=600,height=600,scrollbars=no,resizable=no\'); return false;">' . $this->get_icon_or_label( $this->settings['type'], 'Whatsapp' ) . '</a>';
		}

		if ( '1' === $this->settings['pinterest'] ) {
			$content .= '<a class="' . $ssfd_type . 'pinterest ' . $ssfd_class . '" href="' . $pinterest_url . '" data-pin-custom="true" target="popup" onclick="window.open(\'' . $pinterest_url . '\',\'popup\',\'width=600,height=600,scrollbars=no,resizable=no\'); return false;">' . $this->get_icon_or_label( $this->settings['type'], 'Pinterest' ) . '</a>';
		}

		if ( '1' === $this->settings['email'] ) {
			$content .= '<a class="' . $ssfd_type . 'email ' . $ssfd_class . '" href="' . $email_url . '">' . $this->get_icon_or_label( $this->settings['type'], 'Email' ) . '</a>';
		}

		$content .= '</div>';

		return $content;
	}
}
