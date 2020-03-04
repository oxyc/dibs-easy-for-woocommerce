<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class DIBS_Requests_Checkout {

	public static function get_checkout( $checkout_flow = 'embedded', $order_id = null ) {
		$dibs_settings = get_option( 'woocommerce_dibs_easy_settings' );

		$checkout = array(
			'termsUrl' => wc_get_page_permalink( 'terms' ),
		);
		if ( 'embedded' === $checkout_flow ) {
			$checkout['url']                                     = wc_get_checkout_url();
			$checkout['shipping']['countries']                   = array();
			$checkout['shipping']['merchantHandlesShippingCost'] = true;

			$complete_payment_button_text                                       = ( isset( $dibs_settings['complete_payment_button_text'] ) ) ? $dibs_settings['complete_payment_button_text'] : 'subscribe';
			$checkout['appearance']['textOptions']['completePaymentButtonText'] = $complete_payment_button_text;
		} else {
			$order                                   = wc_get_order( $order_id );
			$checkout['returnUrl']                   = $order->get_checkout_order_received_url();
			$checkout['integrationType']             = 'HostedPaymentPage';
			$checkout['merchantHandlesConsumerData'] = true;
			$checkout['shipping']['countries']       = array();
			$checkout['shipping']['merchantHandlesShippingCost'] = false;
			$checkout['consumer']                                = self::get_consumer_address( $order );
		}

		if ( 'all' !== get_option( 'woocommerce_allowed_countries' ) ) {
			$checkout['shipping']['countries'] = self::get_shipping_countries();
		}
		$allowed_customer_types = ( isset( $dibs_settings['allowed_customer_types'] ) ) ? $dibs_settings['allowed_customer_types'] : 'B2C';
		switch ( $allowed_customer_types ) {
			case 'B2C':
				$checkout['consumerType']['supportedTypes'] = array( 'B2C' );
				break;
			case 'B2B':
				$checkout['consumerType']['supportedTypes'] = array( 'B2B' );
				break;
			case 'B2CB':
				$checkout['consumerType']['supportedTypes'] = array( 'B2C', 'B2B' );
				$checkout['consumerType']['default']        = 'B2C';
				break;
			case 'B2BC':
				$checkout['consumerType']['supportedTypes'] = array( 'B2B', 'B2C' );
				$checkout['consumerType']['default']        = 'B2B';
				break;
			default:
				$checkout['consumerType']['supportedTypes'] = array( 'B2B' );
		} // End switch().

		return $checkout;
	}

	public static function get_shipping_countries() {
		$converted_countries      = array();
		$supported_dibs_countries = dibs_get_supported_countries();
		// Add shipping countries.
		$wc_countries = new WC_Countries();
		$countries    = array_keys( $wc_countries->get_allowed_countries() );

		foreach ( $countries as $country ) {
			$converted_country = dibs_get_iso_3_country( $country );
			$converted_countries[] = array( 'countryCode' => $converted_country );
		}
		return $converted_countries;
	}

	public static function get_consumer_address( $order ) {
		$consumer                                    = array();
		$consumer['email']                           = $order->get_billing_email();
		$consumer['shippingAddress']['addressLine1'] = $order->get_billing_address_1();
		$consumer['shippingAddress']['addressLine2'] = $order->get_billing_address_2();
		$consumer['shippingAddress']['postalCode']   = $order->get_billing_postcode();
		$consumer['shippingAddress']['city']         = $order->get_billing_city();
		$consumer['shippingAddress']['country']      = dibs_get_iso_3_country( $order->get_billing_country() );
		$consumer['phoneNumber']['prefix']           = self::get_phone_prefix( $order );
		$consumer['phoneNumber']['number']           = self::get_phone_number( $order );
		if ( $order->get_billing_company() ) {
			$consumer['company']['name']                 = $order->get_billing_company();
			$consumer['company']['contact']['firstName'] = $order->get_billing_first_name();
			$consumer['company']['contact']['lastName']  = $order->get_billing_last_name();
		} else {
			$consumer['privatePerson']['firstName'] = $order->get_billing_first_name();
			$consumer['privatePerson']['lastName']  = $order->get_billing_last_name();
		}
		return $consumer;
	}

	public static function get_phone_prefix( $order ) {
		$prefix = null;
		if ( substr( $order->get_billing_phone(), 0, 1 ) == '+' ) {
			$prefix = substr( $order->get_billing_phone(), 0, 3 );
		} else {
			$prefix = dibs_get_phone_prefix_for_country( $order->get_billing_country() );
		}
		return $prefix;
	}

	public static function get_phone_number( $order ) {
		$phone_number = null;
		if ( substr( $order->get_billing_phone(), 0, 1 ) == '+' ) {
			$phone_number = substr( $order->get_billing_phone(), strlen( self::get_phone_prefix( $order ) ) );
			$phone_number = str_replace( ' ', '', $phone_number );
		} else {
			$phone_number = str_replace( '-', '', $order->get_billing_phone() );
		}
		return $phone_number;
	}
}
