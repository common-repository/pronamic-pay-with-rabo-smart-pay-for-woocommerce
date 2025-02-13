<?php
/**
 * Widget Payment Status List
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$counts = \wp_count_posts( 'pronamic_payment' );

$states = [
	/* translators: %s: posts count value */
	'payment_completed' => __( '%s completed', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
	/* translators: %s: posts count value */
	'payment_pending'   => __( '%s pending', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
	/* translators: %s: posts count value */
	'payment_cancelled' => __( '%s cancelled', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
	/* translators: %s: posts count value */
	'payment_failed'    => __( '%s failed', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
	/* translators: %s: posts count value */
	'payment_expired'   => __( '%s expired', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
];

$url = \add_query_arg(
	[
		'post_type' => 'pronamic_payment',
	],
	\admin_url( 'edit.php' )
);

?>
<div class="pronamic-pay-status-widget">
	<ul class="pronamic-pay-status-list">

		<?php foreach ( $states as $payment_status => $label ) : ?>

			<li class="<?php echo \esc_attr( 'payment_status-' . $payment_status ); ?>">
				<a href="<?php echo \esc_url( \add_query_arg( 'post_status', $payment_status, $url ) ); ?>">
					<?php

					$count = isset( $counts->$payment_status ) ? $counts->$payment_status : 0;

					echo \wp_kses(
						\sprintf(
							$label,
							'<strong>' . \sprintf(
								/* translators: %s: Number payments */
								\_n( '%s payment', '%s payments', $count, 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
								\number_format_i18n( $count )
							) . '</strong>'
						),
						[
							'strong' => [],
						]
					);

					?>
				</a>
			</li>

		<?php endforeach; ?>

	</ul>
</div>
