<?php
/**
 * Adds the possibility to add Nets Easy data to the end of order confirmation emails.
 *
 * @package Dibs_Easy_For_WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nets_Easy_Email' ) ) :
	/**
	 * The class for email handling for Nets Easy..
	 */
	class Nets_Easy_Email {

		/**
		 * Class constructor.
		 */
		public function __construct() {
			add_action( 'woocommerce_email_after_order_table', array( $this, 'email_extra_information' ), 10, 3 );
		}

		/**
		 * Add Nets Easy related information to WooCommerce order emails.
		 *
		 * @param  object $order WooCommerce order.
		 * @param  bool   $sent_to_admin Email to admin or not.
		 * @param  bool   $plain_text Email html or text format.
		 *
		 * @return void
		 */
		public function email_extra_information( $order, $sent_to_admin, $plain_text = false ) {
			$settings     = get_option( 'woocommerce_dibs_easy_settings' );
			$order_id     = $order->get_id();
			$gateway_used = get_post_meta( $order_id, '_payment_method', true );
			if ( 'dibs_easy' === $gateway_used ) {
				$payment_id     = get_post_meta( $order_id, '_dibs_payment_id', true );
				$customer_card  = get_post_meta( $order_id, 'dibs_customer_card', true );
				$payment_method = get_post_meta( $order_id, 'dibs_payment_method', true );
				$order_date     = wc_format_datetime( $order->get_date_created() );

				if ( $settings['email_text'] ) {
					echo wp_kses_post( wpautop( wptexturize( $settings['email_text'] ) ) );
				}
				if ( $order_date ) {
					echo wp_kses_post( wpautop( wptexturize( __( 'Order date: ', 'dibs-easy-for-woocommerce' ) . $order_date ) ) );
				}
				if ( $payment_id ) {
					echo wp_kses_post( wpautop( wptexturize( __( 'Nets Payment ID: ', 'dibs-easy-for-woocommerce' ) . $payment_id ) ) );
				}
				if ( $payment_method ) {
					echo wp_kses_post( wpautop( wptexturize( __( 'Payment method: ', 'dibs-easy-for-woocommerce' ) . $payment_method ) ) );
				}
				if ( $customer_card ) {
					echo wp_kses_post( wpautop( wptexturize( __( 'Customer card: ', 'dibs-easy-for-woocommerce' ) . $customer_card ) ) );
				}
			}
		}
	}
	new Nets_Easy_Email();
endif;
