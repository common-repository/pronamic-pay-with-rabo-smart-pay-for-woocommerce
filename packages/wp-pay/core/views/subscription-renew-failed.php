<?php
/**
 * Subscription renew failed.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title><?php esc_html_e( 'Subscription Renewal', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?></title>

		<?php wp_print_styles( 'pronamic-pay-redirect' ); ?>
	</head>

	<body>
		<div class="pronamic-pay-redirect-page">
			<div class="pronamic-pay-redirect-container">
				<h1><?php esc_html_e( 'Subscription Renewal', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?></h1>

				<div class="pp-page-section-container">
					<div class="pp-page-section-wrapper">
						<p>
							<?php esc_html_e( 'The subscription can not be renewed.', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
