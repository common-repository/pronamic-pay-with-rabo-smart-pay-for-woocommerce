<?php
/**
 * Status Checker
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Plugin;

/**
 * Status Checker
 *
 * @author  Remco Tolsma
 * @version 2.2.6
 * @since   1.0.0
 */
class StatusChecker {
	/**
	 * Construct a status checker.
	 */
	public function __construct() {
		// Payment status check events are scheduled when payments are started.
		add_action( 'pronamic_pay_payment_status_check', [ $this, 'check_status' ], 10, 2 );

		// Clear scheduled status checks.
		add_action( 'pronamic_payment_status_update', [ $this, 'maybe_clear_scheduled_status_check' ], 10, 1 );
		add_action( 'trashed_post', [ $this, 'clear_scheduled_status_check' ], 10, 1 );
		add_action( 'delete_post', [ $this, 'clear_scheduled_status_check' ], 10, 1 );
	}

	/**
	 * Schedule event.
	 *
	 * @param Payment $payment The payment to schedule the status check event.
	 * @return void
	 */
	public static function schedule_event( $payment ) {
		/*
		 * Schedule status requests
		 * http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
		 *
		 * @todo
		 * Considering the number of status requests per transaction:
		 * - Maximum of five times per transaction;
		 * - Maximum of two times during the expirationPeriod;
		 * - After the expirationPeriod not more often than once per 60 minutes;
		 * - No status request after a final status has been received for a transaction;
		 * - No status request for transactions older than 7 days.
		 */

		// Bail if payment already has a final status (e.g. failed payments).
		$status = $payment->get_status();

		if ( ! empty( $status ) && PaymentStatus::OPEN !== $status ) {
			return;
		}

		// Get delay seconds for first status check.
		$delay = self::get_delay_seconds( 1, $payment );

		\as_schedule_single_action(
			time() + $delay,
			'pronamic_pay_payment_status_check',
			[
				'payment_id' => $payment->get_id(),
				'try'        => 1,
			],
			'pronamic-pay'
		);
	}

	/**
	 * Get the delay seconds for the specified try.
	 *
	 * @param int     $attempt Which try/round to get the delay seconds for.
	 * @param Payment $payment Payment.
	 *
	 * @return int
	 */
	private static function get_delay_seconds( $attempt, $payment ) {
		if ( \in_array(
			$payment->get_payment_method(),
			[
				PaymentMethods::AFTERPAY_NL,
				PaymentMethods::BANK_TRANSFER,
				PaymentMethods::DIRECT_DEBIT,
				PaymentMethods::KLARNA_PAY_LATER,
			],
			true
		) ) {
			switch ( $attempt ) {
				case 1:
					return 15 * MINUTE_IN_SECONDS;

				case 2:
					return 5 * DAY_IN_SECONDS;

				case 3:
					return 10 * DAY_IN_SECONDS;

				case 4:
				default:
					return 14 * DAY_IN_SECONDS;
			}
		}

		// Delays for regular payments.
		switch ( $attempt ) {
			case 1:
				return 15 * MINUTE_IN_SECONDS;

			case 2:
				return 30 * MINUTE_IN_SECONDS;

			case 3:
				return HOUR_IN_SECONDS;

			case 4:
			default:
				return DAY_IN_SECONDS;
		}
	}

	/**
	 * Check status of the specified payment.
	 *
	 * @param int $payment_id The payment ID to check.
	 * @param int $attempt    The try number for this status check.
	 * @return void
	 */
	public function check_status( $payment_id = null, $attempt = 1 ) {
		$payment = get_pronamic_payment( $payment_id );

		// No payment found, unable to check status.
		if ( null === $payment ) {
			return;
		}

		// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
		// - No status request after a final status has been received for a transaction.
		if ( ! empty( $payment->status ) && PaymentStatus::OPEN !== $payment->status ) {
			return;
		}

		// Add note.
		$note = sprintf(
			/* translators: %s: Pronamic Pay */
			__( 'Payment status check at gateway by %s.', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			__( 'Pronamic Pay', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' )
		);

		$payment->add_note( $note );

		// Update payment.
		Plugin::update_payment( $payment, false );

		// Limit number of tries.
		if ( 4 === $attempt ) {
			return;
		}

		// Schedule check if no final status has been received.
		$status = $payment->get_status();

		if ( empty( $status ) || PaymentStatus::OPEN === $status ) {
			$next_attempt = ( $attempt + 1 );

			// Get delay seconds for next status check.
			$delay = self::get_delay_seconds( $next_attempt, $payment );

			\as_schedule_single_action(
				time() + $delay,
				'pronamic_pay_payment_status_check',
				[
					'payment_id' => $payment->get_id(),
					'try'        => $next_attempt,
				],
				'pronamic-pay'
			);
		}
	}

	/**
	 * Maybe clear scheduled status check.
	 *
	 * @param Payment $payment Payment to maybe clear scheduled status checks for.
	 *
	 * @return void
	 */
	public function maybe_clear_scheduled_status_check( $payment ) {
		$status = $payment->get_status();

		// Bail if payment does not have a final payment status.
		if ( empty( $status ) || PaymentStatus::OPEN === $status ) {
			return;
		}

		// Check payment.
		$payment_id = $payment->get_id();

		if ( null === $payment_id ) {
			return;
		}

		// Clear scheduled status check.
		$this->clear_scheduled_status_check( $payment_id );
	}

	/**
	 * Clear scheduled status check.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function clear_scheduled_status_check( $post_id ) {
		// Check post type.
		if ( 'pronamic_payment' !== \get_post_type( $post_id ) ) {
			return;
		}

		// Unschedule action for all 4 tries.
		$args = [
			'payment_id' => $post_id,
		];

		foreach ( range( 1, 4 ) as $attempt ) {
			$args['try'] = $attempt;

			\as_unschedule_action( 'pronamic_pay_payment_status_check', $args );
		}
	}
}
