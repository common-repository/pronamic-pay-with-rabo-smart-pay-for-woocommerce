<?php
/**
 * Cards
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

/**
 * Cards
 *
 * @author  Reüel van der Steege
 * @version 2.7.1
 * @since   2.4.0
 */
class Cards {
	/**
	 * Cards.
	 *
	 * @var array
	 */
	private $cards;

	/**
	 * Cards constructor.
	 */
	public function __construct() {
		$this->register_cards();
	}

	/**
	 * Register cards.
	 *
	 * @return void
	 */
	private function register_cards() {
		$this->cards = [
			// Cards.
			[
				'bic'   => null,
				'brand' => 'american-express',
				'title' => __( 'American Express', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'carta-si',
				'title' => __( 'Carta Si', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'carte-bleue',
				'title' => __( 'Carte Bleue', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'dankort',
				'title' => __( 'Dankort', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'diners-club',
				'title' => __( 'Diners Club', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'discover',
				'title' => __( 'Discover', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'jcb',
				'title' => __( 'JCB', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'maestro',
				'title' => __( 'Maestro', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'mastercard',
				'title' => __( 'Mastercard', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'unionpay',
				'title' => __( 'UnionPay', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => null,
				'brand' => 'visa',
				'title' => __( 'Visa', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],

			// Banks.
			[
				'bic'   => 'abna',
				'brand' => 'abn-amro',
				'title' => __( 'ABN Amro', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'asnb',
				'brand' => 'asn-bank',
				'title' => __( 'ASN Bank', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'bunq',
				'brand' => 'bunq',
				'title' => __( 'bunq', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'hand',
				'brand' => 'handelsbanken',
				'title' => __( 'Handelsbanken', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'ingb',
				'brand' => 'ing',
				'title' => __( 'ING Bank', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'knab',
				'brand' => 'knab',
				'title' => __( 'Knab', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'moyo',
				'brand' => 'moneyou',
				'title' => __( 'Moneyou', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'rabo',
				'brand' => 'rabobank',
				'title' => __( 'Rabobank', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'rbrb',
				'brand' => 'regiobank',
				'title' => __( 'RegioBank', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'snsb',
				'brand' => 'sns',
				'title' => __( 'SNS Bank', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'trio',
				'brand' => 'triodos-bank',
				'title' => __( 'Triodos Bank', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
			[
				'bic'   => 'fvlb',
				'brand' => 'van-lanschot',
				'title' => __( 'Van Lanschot', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			],
		];
	}

	/**
	 * Get card.
	 *
	 * @param string $bic_or_brand 4-letter ISO 9362 Bank Identifier Code (BIC) or brand name.
	 * @return array|null
	 */
	public function get_card( $bic_or_brand ) {
		// Use lowercase BIC or brand without spaces.
		$bic_or_brand = \strtolower( $bic_or_brand );

		$bic_or_brand = \str_replace( ' ', '-', $bic_or_brand );

		// Try to find card.
		$cards = \wp_list_filter(
			$this->cards,
			[
				'bic'   => $bic_or_brand,
				'brand' => $bic_or_brand,
			],
			'OR'
		);

		$card = \array_shift( $cards );

		// Return card details.
		if ( ! empty( $card ) ) {
			return $card;
		}

		// No matching card.
		return null;
	}

	/**
	 * Get card logo URL.
	 *
	 * @param string $brand Brand.
	 *
	 * @return string|null
	 */
	public function get_card_logo_url( $brand ) {
		return sprintf(
			'https://cdn.wp-pay.org/jsdelivr.net/npm/@wp-pay/logos@1.16.0/dist/cards/%1$s/card-%1$s-logo-_x80.svg',
			$brand
		);
	}
}
