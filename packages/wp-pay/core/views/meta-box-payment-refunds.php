<?php
/**
 * Meta Box Payment Refunds
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $payment->refunds ) ) : ?>

	<p>
		<?php \esc_html_e( 'No refunds found for this payment.', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?>
	</p>

<?php else : ?>

	<div class="pronamic-pay-table-responsive">
		<table class="pronamic-pay-table widefat">
			<thead>
				<tr>
					<th scope="col"><?php \esc_html_e( 'Date', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?></th>
					<th scope="col"><?php \esc_html_e( 'Amount', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?></th>
					<th scope="col"><?php \esc_html_e( 'PSP ID', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?></th>
					<th scope="col"><?php \esc_html_e( 'Description', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?></th>
					<th scope="col"><?php \esc_html_e( 'User', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ); ?></th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>

			<tbody>

				<?php foreach ( $payment->refunds as $refund ) : ?>

					<tr>
						<td><?php echo \esc_html( $refund->created_at->format_i18n() ); ?></td>
						<td><?php echo \esc_html( $refund->get_amount()->format_i18n() ); ?></td>
						<td><?php echo \esc_html( $refund->psp_id ); ?></td>
						<td><?php echo \esc_html( $refund->get_description() ); ?></td>
						<td>
							<?php

							$name = __( 'Unknown', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' );

							if ( $refund->created_by->ID > 0 ) {
								$name = $refund->created_by->display_name;
							}

							echo \esc_html( $name );

							?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>
	</div>

<?php endif; ?>
