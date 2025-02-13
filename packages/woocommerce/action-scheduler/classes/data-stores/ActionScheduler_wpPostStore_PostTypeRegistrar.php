<?php

/**
 * Class ActionScheduler_wpPostStore_PostTypeRegistrar
 * @codeCoverageIgnore
 */
class ActionScheduler_wpPostStore_PostTypeRegistrar {
	public function register() {
		register_post_type( ActionScheduler_wpPostStore::POST_TYPE, $this->post_type_args() );
	}

	/**
	 * Build the args array for the post type definition
	 *
	 * @return array
	 */
	protected function post_type_args() {
		$args = array(
			'label' => __( 'Scheduled Actions', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			'description' => __( 'Scheduled actions are hooks triggered on a cetain date and time.', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			'public' => false,
			'map_meta_cap' => true,
			'hierarchical' => false,
			'supports' => array('title', 'editor','comments'),
			'rewrite' => false,
			'query_var' => false,
			'can_export' => true,
			'ep_mask' => EP_NONE,
			'labels' => array(
				'name' => __( 'Scheduled Actions', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'singular_name' => __( 'Scheduled Action', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'menu_name' => _x( 'Scheduled Actions', 'Admin menu name', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'add_new' => __( 'Add', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'add_new_item' => __( 'Add New Scheduled Action', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'edit' => __( 'Edit', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'edit_item' => __( 'Edit Scheduled Action', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'new_item' => __( 'New Scheduled Action', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'view' => __( 'View Action', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'view_item' => __( 'View Action', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'search_items' => __( 'Search Scheduled Actions', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'not_found' => __( 'No actions found', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
				'not_found_in_trash' => __( 'No actions found in trash', 'pronamic-pay-with-rabo-smart-pay-for-woocommerce' ),
			),
		);

		$args = apply_filters('action_scheduler_post_type_args', $args);
		return $args;
	}
}
 