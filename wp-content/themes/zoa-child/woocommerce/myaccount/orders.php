<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>
<div class="box-list">
<?php if ( $has_orders ) : ?>
			<?php foreach ( $customer_orders->orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
				<div class="box order order__row--status-<?php echo esc_attr( $order->get_status() ); ?>">
					<div class="box__main order__main">
						<div class="box__main__details order__main__details">
						
							<h3 class="order__number heading heading--xsmall"><span class="label"><?php esc_html_e( 'Order Number', 'zoa' ); ?>: </span><span class="value"><?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?></span></h3>
						
							<p class="order__date serif">
									<span class="label"><?php esc_html_e( 'Order Date', 'zoa' ); ?></span>
									<span class="value"><time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time></span>
							</p>
						
							<div class="order-status serif">
								<span class="label"><?php esc_html_e( 'Order Status', 'zoa' ); ?></span>
								<span class="value icon-<?php echo esc_attr( $order->get_status() ); ?>">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</span>
							</div>
							
							
						
							<p class="order__total serif display--small-up">
								<span class="label"><?php esc_html_e( 'Total', 'zoa' ); ?></span>
								<span class="value"><?php
								/* translators: 1: formatted order total 2: total order items */
								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								?></span>
							</p>
						</div>
					
					<div class="box__actions order__actions">
						<?php
								$actions = wc_get_account_orders_actions( $order );
								
								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) {
										echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								
								if (isOrderAllowCancel($order))
								{
									echo '<a data-id="'. $order->get_id() .'" href="javascript:void(0)" class="button cancel_order_btn">' . __('Cancel', 'zoa') . '</a>';
								}
						?>
						<!--<span class="cta cta--underlined txt--upper show-more">
							<span class="more">Show More</span>
						</span>-->
					</div>
					</div>
					</div>
           
			<?php endforeach; ?>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php _e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php _e( 'Go shop', 'woocommerce' ) ?>
		</a>
		<?php _e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>
</div>
<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
